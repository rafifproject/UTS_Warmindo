<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\ItemPenjualan;
use App\Models\RiwayatStatusPesanan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KontrollerDapur extends Controller
{
    public function dasbor(Request $request)
    {
        $hari = Carbon::today();

        $totalMasuk     = Penjualan::where('payment_status', 'paid')->whereDate('paid_at', $hari)->count();
        $sedangDiproses = Penjualan::where('payment_status', 'paid')->where('order_status', 'preparing')->count();
        $siapDisajikan  = Penjualan::where('payment_status', 'paid')->where('order_status', 'ready_to_serve')->count();
        $selesai        = Penjualan::where('payment_status', 'paid')->where('order_status', 'completed')->whereDate('updated_at', $hari)->count();

        $query = Penjualan::with('itemPenjualan')->where('payment_status', 'paid');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('order_status', $request->status);
        } else {
            $query->whereIn('order_status', ['queued', 'preparing', 'ready_to_serve', 'completed']);
        }

        if ($request->filled('search')) {
            $cari = $request->search;
            $query->where(function ($sub) use ($cari) {
                $sub->where('sale_number', 'like', "%{$cari}%")
                    ->orWhere('table_number', 'like', "%{$cari}%")
                    ->orWhereHas('itemPenjualan', function ($iq) use ($cari) {
                        $iq->where('product_name', 'like', "%{$cari}%");
                    });
            });
        }

        $urutan = $request->query('sort', 'latest');
        if ($urutan === 'oldest') {
            $query->oldest('paid_at');
        } else {
            $query->latest('paid_at');
        }

        $boardBase = Penjualan::with(['itemPenjualan.produk'])->where('payment_status', 'paid');
        if ($request->filled('search')) {
            $cari = $request->search;
            $boardBase->where(function ($sub) use ($cari) {
                $sub->where('sale_number', 'like', "%{$cari}%")
                    ->orWhere('table_number', 'like', "%{$cari}%")
                    ->orWhereHas('itemPenjualan', function ($iq) use ($cari) {
                        $iq->where('product_name', 'like', "%{$cari}%");
                    });
            });
        }

        $queuedOrders    = (clone $boardBase)->where('order_status', 'queued')->oldest('paid_at')->get();
        $preparingOrders = (clone $boardBase)->where('order_status', 'preparing')->oldest('updated_at')->get();
        $readyOrders     = (clone $boardBase)->where('order_status', 'ready_to_serve')->oldest('updated_at')->get();

        $daftarPesanan = $query->paginate(10)->withQueryString();
        $orders        = $daftarPesanan;
        $viewMode      = $request->query('view', 'board');

        return view('dapur.dasbor', compact(
            'totalMasuk',
            'sedangDiproses',
            'siapDisajikan',
            'selesai',
            'queuedOrders',
            'preparingOrders',
            'readyOrders',
            'daftarPesanan',
            'orders',
            'viewMode'
        ));
    }

    public function detailMasakan($nomorPesanan)
    {
        $penjualan = Penjualan::with(['itemPenjualan', 'riwayatStatus'])
            ->where('sale_number', $nomorPesanan)->firstOrFail();

        return view('dapur.detail-masakan', ['sale' => $penjualan]);
    }

    public function perbaruiStatus(Request $request, $nomorPesanan)
    {
        $penjualan = Penjualan::where('sale_number', $nomorPesanan)->firstOrFail();

        $request->validate([
            'status' => 'required|in:queued,preparing,ready_to_serve,completed',
            'notes'  => 'nullable|string|max:255',
        ]);

        $statusLama = $penjualan->order_status;
        $statusBaru = $request->status;

        $penjualan->update(['order_status' => $statusBaru]);
        $penjualan->itemPenjualan()->update(['status' => $statusBaru]);

        RiwayatStatusPesanan::create([
            'sale_id'    => $penjualan->id,
            'from_status' => $statusLama,
            'to_status'  => $statusBaru,
            'staff_name' => 'Dapur: ' . (auth()->user()->name ?? 'Petugas Dapur'),
            'user_id'    => auth()->id(),
            'notes'      => $request->notes ?? ('Status masakan diubah menjadi ' . $statusBaru),
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui menjadi: ' . $statusBaru);
    }

    public function riwayat(Request $request)
    {
        $query = RiwayatStatusPesanan::with(['penjualan.itemPenjualan'])->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('to_status', $request->status);
        }

        if ($request->filled('search')) {
            $cari = $request->search;
            $query->whereHas('penjualan', function ($sq) use ($cari) {
                $sq->where('sale_number', 'like', "%{$cari}%")
                   ->orWhere('table_number', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $riwayatLog = $query->paginate(15)->withQueryString();

        return view('dapur.riwayat', ['histories' => $riwayatLog]);
    }

    public function dashboard(Request $request)
    {
        return $this->dasbor($request);
    }

    public function detail($saleNumber)
    {
        return $this->detailMasakan($saleNumber);
    }

    public function updateStatus(Request $request, $saleNumber)
    {
        return $this->perbaruiStatus($request, $saleNumber);
    }

    public function history(Request $request)
    {
        return $this->riwayat($request);
    }
}
