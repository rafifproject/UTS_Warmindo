<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\ItemPenjualan;
use App\Models\Produk;
use App\Models\MutasiStok;
use App\Models\RiwayatStatusPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KontrollerKasir extends Controller
{
    public function dasbor()
    {
        $hari = Carbon::today();

        $pesananMenunggu       = Penjualan::where('payment_status', 'pending')->count();
        $jumlahTransaksiHariIni = Penjualan::where('payment_status', 'paid')
            ->whereDate('paid_at', $hari)->count();
        $omzetHariIni          = Penjualan::where('payment_status', 'paid')
            ->whereDate('paid_at', $hari)->sum('total_amount');

        $pesananTerbaru = Penjualan::with('itemPenjualan')->latest()->take(6)->get();

        $waitingCount           = $pesananMenunggu;
        $todayTransactionsCount = $jumlahTransaksiHariIni;
        $todayRevenue           = $omzetHariIni;
        $recentOrders           = $pesananTerbaru;

        return view('kasir.dasbor', compact(
            'pesananMenunggu',
            'jumlahTransaksiHariIni',
            'omzetHariIni',
            'pesananTerbaru',
            'waitingCount',
            'todayTransactionsCount',
            'todayRevenue',
            'recentOrders'
        ));
    }

    public function daftarTunggu(Request $request)
    {
        $query = Penjualan::with('itemPenjualan')->where('payment_status', 'pending');

        if ($request->filled('search')) {
            $cari = $request->search;
            $query->where(function ($q) use ($cari) {
                $q->where('sale_number', 'like', "%{$cari}%")
                  ->orWhere('table_number', 'like', "%{$cari}%")
                  ->orWhereHas('itemPenjualan', function ($iq) use ($cari) {
                      $iq->where('product_name', 'like', "%{$cari}%");
                  });
            });
        }

        $daftarPesanan = $query->latest()->paginate(10)->withQueryString();

        return view('kasir.daftar-tunggu', ['orders' => $daftarPesanan]);
    }

    public function cariPesanan(Request $request)
    {
        $tab   = $request->query('tab', 'all');
        $query = Penjualan::with('itemPenjualan')->latest();

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

        $hasilPencarian = $query->paginate(12)->withQueryString();

        return view('kasir.cari-pesanan', ['results' => $hasilPencarian, 'tab' => $tab]);
    }

    public function detailPesanan($nomorPesanan)
    {
        $penjualan = Penjualan::with(['itemPenjualan.produk', 'pengguna'])
            ->where('sale_number', $nomorPesanan)->firstOrFail();

        return view('kasir.detail-pesanan', ['sale' => $penjualan]);
    }

    public function formPembayaran($nomorPesanan)
    {
        $penjualan = Penjualan::with('itemPenjualan')->where('sale_number', $nomorPesanan)->firstOrFail();

        if ($penjualan->payment_status === 'paid') {
            return redirect()->route('kasir.receipt', $penjualan->sale_number)
                ->with('info', 'Pesanan ini sudah dibayar sebelumnya.');
        }

        return view('kasir.form-pembayaran', ['sale' => $penjualan]);
    }

    public function prosesPembayaran(Request $request, $nomorPesanan)
    {
        $penjualan = Penjualan::with('itemPenjualan.produk')
            ->where('sale_number', $nomorPesanan)->firstOrFail();

        if ($penjualan->payment_status === 'paid') {
            return redirect()->route('kasir.receipt', $penjualan->sale_number)
                ->with('error', 'Pesanan ini telah lunas dan tidak dapat dibayar ulang.');
        }

        $request->validate([
            'payment_method' => 'required|in:cash,qris,transfer',
            'cash_received'  => 'required_if:payment_method,cash|numeric|min:0',
        ]);

        $uangDiterima = $request->payment_method === 'cash'
            ? (float)$request->input('cash_received')
            : $penjualan->total_amount;

        if ($request->payment_method === 'cash' && $uangDiterima < $penjualan->total_amount) {
            return back()->withInput()
                ->with('show_insufficient_cash_modal', true)
                ->with('shortage', $penjualan->total_amount - $uangDiterima);
        }

        $kembalian = $request->payment_method === 'cash'
            ? ($uangDiterima - $penjualan->total_amount)
            : 0;

        try {
            DB::transaction(function () use ($penjualan, $request, $uangDiterima, $kembalian) {
                $penjualanTerkunci = Penjualan::lockForUpdate()->find($penjualan->id);

                if ($penjualanTerkunci->payment_status === 'paid') {
                    throw new \Exception('Pesanan sudah dibayar oleh kasir lain.');
                }

                foreach ($penjualanTerkunci->itemPenjualan as $item) {
                    $produk = Produk::lockForUpdate()->find($item->product_id);

                    if (!$produk) {
                        throw new \Exception('Produk ' . $item->product_name . ' tidak ditemukan.');
                    }

                    if ($produk->stock < $item->quantity) {
                        throw new \Exception('Stok tidak mencukupi untuk ' . $produk->name . '. Sisa stok: ' . $produk->stock);
                    }

                    $stokSebelum = $produk->stock;
                    $produk->decrement('stock', $item->quantity);
                    $stokSesudah = $produk->fresh()->stock;

                    MutasiStok::create([
                        'product_id'       => $produk->id,
                        'type'             => 'out',
                        'reference_type'   => 'sale',
                        'reference_id'     => $penjualanTerkunci->id,
                        'reference_number' => $penjualanTerkunci->sale_number,
                        'quantity'         => $item->quantity,
                        'before_stock'     => $stokSebelum,
                        'after_stock'      => $stokSesudah,
                        'notes'            => 'Penjualan ke kasir ' . (auth()->user()->name ?? 'Kasir'),
                        'user_id'          => auth()->id(),
                    ]);

                    $item->update(['status' => 'queued']);
                }

                $penjualanTerkunci->update([
                    'payment_status' => 'paid',
                    'order_status'   => 'queued',
                    'payment_method' => $request->payment_method,
                    'cash_received'  => $uangDiterima,
                    'cash_change'    => $kembalian,
                    'paid_at'        => Carbon::now(),
                    'user_id'        => auth()->id(),
                ]);

                RiwayatStatusPesanan::create([
                    'sale_id'    => $penjualanTerkunci->id,
                    'from_status' => 'waiting_payment',
                    'to_status'  => 'queued',
                    'staff_name' => 'Kasir: ' . (auth()->user()->name ?? 'Staff'),
                    'user_id'    => auth()->id(),
                    'notes'      => 'Pembayaran ' . strtoupper($request->payment_method) . ' berhasil dikonfirmasi.',
                ]);
            });
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        session()->flash('payment_success_modal', true);

        return redirect()->route('kasir.receipt', $penjualan->sale_number);
    }

    public function struk($nomorPesanan)
    {
        $penjualan = Penjualan::with(['itemPenjualan', 'pengguna'])
            ->where('sale_number', $nomorPesanan)->firstOrFail();

        return view('kasir.struk', ['sale' => $penjualan]);
    }

    public function dashboard()
    {
        return $this->dasbor();
    }

    public function waitingList(Request $request)
    {
        return $this->daftarTunggu($request);
    }

    public function searchOrders(Request $request)
    {
        return $this->cariPesanan($request);
    }

    public function detail($saleNumber)
    {
        return $this->detailPesanan($saleNumber);
    }

    public function paymentForm($saleNumber)
    {
        return $this->formPembayaran($saleNumber);
    }

    public function processPayment(Request $request, $saleNumber)
    {
        return $this->prosesPembayaran($request, $saleNumber);
    }

    public function receipt($saleNumber)
    {
        return $this->struk($saleNumber);
    }
}
