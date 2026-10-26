<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\MutasiStok;
use App\Models\RiwayatStatusPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KontrollerPenjualan extends Controller
{
    public function indeks(Request $request)
    {
        $query = Penjualan::with(['itemPenjualan', 'pengguna'])->latest();

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
            $cari = $request->search;
            $query->where(function ($q) use ($cari) {
                $q->where('sale_number', 'like', "%{$cari}%")
                  ->orWhere('table_number', 'like', "%{$cari}%")
                  ->orWhere('customer_name', 'like', "%{$cari}%");
            });
        }

        $semuaPenjualan = $query->paginate(12)->withQueryString();

        return view('admin.penjualan.indeks', ['sales' => $semuaPenjualan]);
    }

    public function detail(Penjualan $sale)
    {
        $sale->load(['itemPenjualan.produk', 'pengguna', 'riwayatStatus']);
        return view('admin.penjualan.detail', compact('sale'));
    }

    public function batalkan(Request $request, Penjualan $sale)
    {
        if ($sale->payment_status === 'cancelled') {
            return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.');
        }

        DB::transaction(function () use ($sale, $request) {
            $penjualanTerkunci = Penjualan::lockForUpdate()->find($sale->id);

            if ($penjualanTerkunci->payment_status === 'paid') {
                foreach ($penjualanTerkunci->itemPenjualan as $item) {
                    $produk = Produk::lockForUpdate()->find($item->product_id);
                    if ($produk) {
                        $stokSebelum = $produk->stock;
                        $produk->increment('stock', $item->quantity);
                        $stokSesudah = $produk->fresh()->stock;

                        MutasiStok::create([
                            'product_id'       => $produk->id,
                            'type'             => 'in',
                            'reference_type'   => 'adjustment',
                            'reference_id'     => $penjualanTerkunci->id,
                            'reference_number' => $penjualanTerkunci->sale_number,
                            'quantity'         => $item->quantity,
                            'before_stock'     => $stokSebelum,
                            'after_stock'      => $stokSesudah,
                            'notes'            => 'Pengembalian stok dari pembatalan transaksi ' . $penjualanTerkunci->sale_number,
                            'user_id'          => auth()->id(),
                        ]);
                    }
                }
            }

            $penjualanTerkunci->update([
                'payment_status' => 'cancelled',
                'order_status'   => 'cancelled',
                'notes'          => $penjualanTerkunci->notes . ' [Dibatalkan oleh ' . (auth()->user()->name ?? 'Admin') . ': ' . ($request->reason ?? 'Tanpa alasan') . ']',
            ]);

            RiwayatStatusPesanan::create([
                'sale_id'    => $penjualanTerkunci->id,
                'from_status' => $penjualanTerkunci->order_status,
                'to_status'  => 'cancelled',
                'staff_name' => 'Admin: ' . (auth()->user()->name ?? 'Admin'),
                'user_id'    => auth()->id(),
                'notes'      => 'Pesanan dibatalkan.',
            ]);
        });

        return back()->with('success', 'Transaksi ' . $sale->sale_number . ' berhasil dibatalkan dan stok telah dikembalikan.');
    }

    public function index(Request $request)
    {
        return $this->indeks($request);
    }

    public function show(Penjualan $sale)
    {
        return $this->detail($sale);
    }

    public function cancel(Request $request, Penjualan $sale)
    {
        return $this->batalkan($request, $sale);
    }
}
