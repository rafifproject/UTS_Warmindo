<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KitchenController extends Controller
{
    public function dashboard(Request $request)
    {
        $today = Carbon::today();

        $totalMasuk = Sale::where('payment_status', 'paid')
            ->whereDate('paid_at', $today)
            ->count();

        $sedangDiproses = Sale::where('payment_status', 'paid')
            ->where('order_status', 'preparing')
            ->count();

        $siapDisajikan = Sale::where('payment_status', 'paid')
            ->where('order_status', 'ready_to_serve')
            ->count();

        $selesai = Sale::where('payment_status', 'paid')
            ->where('order_status', 'completed')
            ->whereDate('updated_at', $today)
            ->count();

        $query = Sale::with('items')
            ->where('payment_status', 'paid');

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('order_status', $request->status);
        } else {
            // Default: show active queue
            $query->whereIn('order_status', ['queued', 'preparing', 'ready_to_serve', 'completed']);
        }

        // Search Filter
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($sub) use ($s) {
                $sub->where('sale_number', 'like', "%{$s}%")
                    ->orWhere('table_number', 'like', "%{$s}%")
                    ->orWhereHas('items', function ($iq) use ($s) {
                        $iq->where('product_name', 'like', "%{$s}%");
                    });
            });
        }

        $sort = $request->query('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest('paid_at');
        } else {
            $query->latest('paid_at');
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('kitchen.dashboard', compact(
            'totalMasuk',
            'sedangDiproses',
            'siapDisajikan',
            'selesai',
            'orders'
        ));
    }

    public function detail($saleNumber)
    {
        $sale = Sale::with(['items', 'statusHistories'])->where('sale_number', $saleNumber)->firstOrFail();
        return view('kitchen.detail', compact('sale'));
    }

    public function updateStatus(Request $request, $saleNumber)
    {
        $sale = Sale::where('sale_number', $saleNumber)->firstOrFail();

        $request->validate([
            'status' => 'required|in:queued,preparing,ready_to_serve,completed',
            'notes' => 'nullable|string|max:255',
        ]);

        $fromStatus = $sale->order_status;
        $toStatus = $request->status;

        $sale->update(['order_status' => $toStatus]);
        $sale->items()->update(['status' => $toStatus]);

        // Record history
        OrderStatusHistory::create([
            'sale_id' => $sale->id,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'staff_name' => 'Dapur: ' . (auth()->user()->name ?? 'Petugas Dapur'),
            'user_id' => auth()->id(),
            'notes' => $request->notes ?? ('Status masakan diubah menjadi ' . $toStatus),
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui menjadi: ' . $toStatus);
    }

    public function history(Request $request)
    {
        $query = OrderStatusHistory::with(['sale.items'])->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('to_status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('sale', function ($sq) use ($s) {
                $sq->where('sale_number', 'like', "%{$s}%")
                   ->orWhere('table_number', 'like', "%{$s}%");
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $histories = $query->paginate(15)->withQueryString();

        return view('kitchen.history', compact('histories'));
    }
}
