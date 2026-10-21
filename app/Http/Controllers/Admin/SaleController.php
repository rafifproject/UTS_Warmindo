<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Models\StockMutation;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['items', 'user'])->latest();

        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('order_type') && $request->order_type !== 'all') {
            $query->where('order_type', $request->order_type);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('sale_number', 'like', "%{$s}%")
                  ->orWhere('table_number', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%");
            });
        }

        $sales = $query->paginate(12)->withQueryString();

        return view('admin.sales.index', compact('sales'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['items.product', 'user', 'statusHistories']);
        return view('admin.sales.show', compact('sale'));
    }

    public function cancel(Request $request, Sale $sale)
    {
        if ($sale->payment_status === 'cancelled') {
            return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.');
        }

        DB::transaction(function () use ($sale, $request) {
            $lockedSale = Sale::lockForUpdate()->find($sale->id);

            // If the sale was already paid, restore the stock
            if ($lockedSale->payment_status === 'paid') {
                foreach ($lockedSale->items as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);
                    if ($product) {
                        $beforeStock = $product->stock;
                        $product->increment('stock', $item->quantity);
                        $afterStock = $product->fresh()->stock;

                        StockMutation::create([
                            'product_id' => $product->id,
                            'type' => 'in',
                            'reference_type' => 'adjustment',
                            'reference_id' => $lockedSale->id,
                            'reference_number' => $lockedSale->sale_number,
                            'quantity' => $item->quantity,
                            'before_stock' => $beforeStock,
                            'after_stock' => $afterStock,
                            'notes' => 'Pengembalian stok dari pembatalan transaksi ' . $lockedSale->sale_number,
                            'user_id' => auth()->id(),
                        ]);
                    }
                }
            }

            $lockedSale->update([
                'payment_status' => 'cancelled',
                'order_status' => 'cancelled',
                'notes' => $lockedSale->notes . ' [Dibatalkan oleh ' . (auth()->user()->name ?? 'Admin') . ': ' . ($request->reason ?? 'Tanpa alasan') . ']',
            ]);

            OrderStatusHistory::create([
                'sale_id' => $lockedSale->id,
                'from_status' => $lockedSale->order_status,
                'to_status' => 'cancelled',
                'staff_name' => 'Admin: ' . (auth()->user()->name ?? 'Admin'),
                'user_id' => auth()->id(),
                'notes' => 'Pesanan dibatalkan.',
            ]);
        });

        return back()->with('success', 'Transaksi ' . $sale->sale_number . ' berhasil dibatalkan dan stok telah dikembalikan.');
    }
}
