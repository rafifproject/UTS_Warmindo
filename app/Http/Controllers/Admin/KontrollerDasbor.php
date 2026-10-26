<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KontrollerDasbor extends Controller
{
    public function indeks()
    {
        $totalProduk      = Produk::count();
        $totalTransaksi   = Penjualan::where('payment_status', 'paid')->count();
        $totalPendapatan  = Penjualan::where('payment_status', 'paid')->sum('total_amount');

        $penjualan7Hari = [];
        $label7Hari     = [];
        for ($i = 6; $i >= 0; $i--) {
            $hari             = Carbon::today()->subDays($i);
            $label7Hari[]     = $hari->translatedFormat('d M');
            $jumlahHarian     = Penjualan::where('payment_status', 'paid')
                ->whereDate('paid_at', $hari)->sum('total_amount');
            $penjualan7Hari[] = (float)$jumlahHarian;
        }

        $metodePembayaran = Penjualan::where('payment_status', 'paid')
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        $labelMetode  = [];
        $jumlahMetode = [];
        foreach ($metodePembayaran as $mp) {
            $labelMetode[]  = strtoupper($mp->payment_method ?? 'TUNAI');
            $jumlahMetode[] = (int)$mp->count;
        }

        $produkStokMenipis = Produk::with('kategori')
            ->whereColumn('stock', '<=', 'min_stock')
            ->take(5)->get();

        $penjualanTerbaru = Penjualan::with('itemPenjualan')
            ->where('payment_status', 'paid')
            ->latest('paid_at')
            ->take(5)->get();

        return view('admin.dasbor', [
            'totalProducts'    => $totalProduk,
            'totalTransactions' => $totalTransaksi,
            'totalRevenue'     => $totalPendapatan,
            'labels7Days'      => $label7Hari,
            'sales7Days'       => $penjualan7Hari,
            'payLabels'        => $labelMetode,
            'payCounts'        => $jumlahMetode,
            'lowStockProducts' => $produkStokMenipis,
            'recentSales'      => $penjualanTerbaru,
        ]);
    }

    public function index()
    {
        return $this->indeks();
    }
}
