<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\StockMutation;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KasirController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

        $waitingCount = Sale::where('payment_status', 'pending')->count();
        $todayTransactionsCount = Sale::where('payment_status', 'paid')
            ->whereDate('paid_at', $today)
            ->count();
        $todayRevenue = Sale::where('payment_status', 'paid')
            ->whereDate('paid_at', $today)
            ->sum('total_amount');

        $recentOrders = Sale::with('items')
            ->latest()
            ->take(6)
            ->get();

        return view('kasir.dashboard', compact(
            'waitingCount',
            'todayTransactionsCount',
            'todayRevenue',
            'recentOrders'
        ));
    }

    public function waitingList(Request $request)
    {
        $query = Sale::with('items')
            ->where('payment_status', 'pending');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                  ->orWhere('table_number', 'like', "%{$search}%")
                  ->orWhereHas('items', function ($iq) use ($search) {
                      $iq->where('product_name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('kasir.waiting-list', compact('orders'));
    }

    public function searchOrders(Request $request)
    {
        $tab = $request->query('tab', 'all'); // all, dine_in, take_away
        $query = Sale::with('items')->latest();

        if ($tab === 'dine_in') {
            $query->where('order_type', 'dine_in');
        } elseif ($tab === 'take_away') {
            $query->where('order_type', 'take_away');
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('sale_number', 'like', "%{$q}%")
                    ->orWhere('table_number', 'like', "%{$q}%")
                    ->orWhere('customer_name', 'like', "%{$q}%");
            });
        }

        $results = $query->paginate(12)->withQueryString();

        return view('kasir.search', compact('results', 'tab'));
    }

    public function detail($saleNumber)
    {
        $sale = Sale::with(['items.product', 'user'])->where('sale_number', $saleNumber)->firstOrFail();
        return view('kasir.detail', compact('sale'));
    }

    public function paymentForm($saleNumber)
    {
        $sale = Sale::with('items')->where('sale_number', $saleNumber)->firstOrFail();

        if ($sale->payment_status === 'paid') {
            return redirect()->route('kasir.receipt', $sale->sale_number)->with('info', 'Pesanan ini sudah dibayar sebelumnya.');
        }

        return view('kasir.payment-form', compact('sale'));
    }

    public function processPayment(Request $request, $saleNumber)
    {
        $sale = Sale::with('items.product')->where('sale_number', $saleNumber)->firstOrFail();

        // Prevent double payment
        if ($sale->payment_status === 'paid') {
            return redirect()->route('kasir.receipt', $sale->sale_number)->with('error', 'Pesanan ini telah lunas dan tidak dapat dibayar ulang.');
        }

        $request->validate([
            'payment_method' => 'required|in:cash,qris,transfer',
            'cash_received' => 'required_if:payment_method,cash|numeric|min:0',
        ]);

        $cashReceived = $request->payment_method === 'cash' ? (float)$request->input('cash_received') : $sale->total_amount;

        // Validation for Insufficient Cash (Screen 47)
        if ($request->payment_method === 'cash' && $cashReceived < $sale->total_amount) {
            return back()->withInput()->with('show_insufficient_cash_modal', true)->with('shortage', $sale->total_amount - $cashReceived);
        }

        $cashChange = $request->payment_method === 'cash' ? ($cashReceived - $sale->total_amount) : 0;

        // Execute Atomic Stock Reduction & Payment Recording
        DB::transaction(function () use ($sale, $request, $cashReceived, $cashChange) {
            $lockedSale = Sale::lockForUpdate()->find($sale->id);

            if ($lockedSale->payment_status === 'paid') {
                throw new \Exception('Pesanan sudah dibayar oleh kasir lain.');
            }

            // Decrement Stock safely & record mutations
            foreach ($lockedSale->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);

                if (!$product) {
                    throw new \Exception('Produk ' . $item->product_name . ' tidak ditemukan.');
                }

                if ($product->stock < $item->quantity) {
                    throw new \Exception('Stok tidak mencukupi untuk ' . $product->name . '. Sisa stok: ' . $product->stock);
                }

                $beforeStock = $product->stock;
                $product->decrement('stock', $item->quantity);
                $afterStock = $product->fresh()->stock;

                // Log audit mutation
                StockMutation::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'reference_type' => 'sale',
                    'reference_id' => $lockedSale->id,
                    'reference_number' => $lockedSale->sale_number,
                    'quantity' => $item->quantity,
                    'before_stock' => $beforeStock,
                    'after_stock' => $afterStock,
                    'notes' => 'Penjualan ke kasir ' . (auth()->user()->name ?? 'Kasir'),
                    'user_id' => auth()->id(),
                ]);

                // Update item status to queued
                $item->update(['status' => 'queued']);
            }

            // Update Sale Status
            $lockedSale->update([
                'payment_status' => 'paid',
                'order_status' => 'queued',
                'payment_method' => $request->payment_method,
                'cash_received' => $cashReceived,
                'cash_change' => $cashChange,
                'paid_at' => Carbon::now(),
                'user_id' => auth()->id(),
            ]);

            // Add Order Status History for Kitchen tracking
            OrderStatusHistory::create([
                'sale_id' => $lockedSale->id,
                'from_status' => 'waiting_payment',
                'to_status' => 'queued',
                'staff_name' => 'Kasir: ' . (auth()->user()->name ?? 'Staff'),
                'user_id' => auth()->id(),
                'notes' => 'Pembayaran ' . strtoupper($request->payment_method) . ' berhasil dikonfirmasi.',
            ]);
        });

        // Set session flash for Payment Success Modal (Screen 48)
        session()->flash('payment_success_modal', true);

        return redirect()->route('kasir.receipt', $sale->sale_number);
    }

    public function receipt($saleNumber)
    {
        $sale = Sale::with(['items', 'user'])->where('sale_number', $saleNumber)->firstOrFail();
        return view('kasir.receipt', compact('sale'));
    }
}
