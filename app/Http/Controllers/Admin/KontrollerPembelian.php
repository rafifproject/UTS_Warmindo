<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Models\ItemPembelian;
use App\Models\Produk;
use App\Models\Pemasok;
use App\Models\MutasiStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KontrollerPembelian extends Controller
{
    public function indeks(Request $request)
    {
        $query = Pembelian::with(['pemasok', 'pengguna'])->latest();

        if ($request->filled('supplier_id') && $request->supplier_id !== 'all') {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('purchase_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('search')) {
            $cari = $request->search;
            $query->where('purchase_number', 'like', "%{$cari}%");
        }

        $semuaPembelian = $query->paginate(10)->withQueryString();
        $semuaPemasok   = Pemasok::where('is_active', true)->get();

        return view('admin.pembelian.indeks', [
            'purchases' => $semuaPembelian,
            'suppliers' => $semuaPemasok,
        ]);
    }

    public function buat()
    {
        $semuaPemasok = Pemasok::where('is_active', true)->get();
        $semuaProduk  = Produk::orderBy('name')->get();

        $jumlah = Pembelian::count();
        $nomorBerikut = 'PB-' . str_pad($jumlah + 1, 3, '0', STR_PAD_LEFT);

        return view('admin.pembelian.buat', [
            'suppliers'  => $semuaPemasok,
            'products'   => $semuaProduk,
            'nextNumber' => $nomorBerikut,
        ]);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'purchase_number'      => 'required|string|unique:purchases,purchase_number',
            'supplier_id'          => 'required|exists:suppliers,id',
            'purchase_date'        => 'required|date',
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.unit_price'   => 'required|numeric|min:0',
            'notes'                => 'nullable|string',
        ], [
            'items.required'       => 'Minimal pilih 1 item untuk transaksi pembelian.',
            'items.*.quantity.min' => 'Kuantitas produk minimal 1.',
        ]);

        DB::transaction(function () use ($request) {
            $totalHarga = 0;
            $daftarItem = [];

            foreach ($request->items as $item) {
                $subtotal     = $item['quantity'] * $item['unit_price'];
                $totalHarga  += $subtotal;
                $daftarItem[] = [
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal'   => $subtotal,
                ];
            }

            $pembelian = Pembelian::create([
                'purchase_number' => $request->purchase_number,
                'supplier_id'     => $request->supplier_id,
                'user_id'         => auth()->id(),
                'purchase_date'   => $request->purchase_date,
                'total_amount'    => $totalHarga,
                'status'          => 'completed',
                'notes'           => $request->notes,
            ]);

            foreach ($daftarItem as $data) {
                $data['purchase_id'] = $pembelian->id;
                ItemPembelian::create($data);

                $produk      = Produk::lockForUpdate()->find($data['product_id']);
                $stokSebelum = $produk->stock;
                $produk->increment('stock', $data['quantity']);
                $stokSesudah = $produk->fresh()->stock;

                MutasiStok::create([
                    'product_id'       => $produk->id,
                    'type'             => 'in',
                    'reference_type'   => 'purchase',
                    'reference_id'     => $pembelian->id,
                    'reference_number' => $pembelian->purchase_number,
                    'quantity'         => $data['quantity'],
                    'before_stock'     => $stokSebelum,
                    'after_stock'      => $stokSesudah,
                    'notes'            => 'Pembelian dari supplier: ' . ($pembelian->pemasok->name ?? 'Supplier'),
                    'user_id'          => auth()->id(),
                ]);
            }
        });

        return redirect()->route('admin.purchases.index')
            ->with('success', 'Transaksi pembelian berhasil disimpan dan stok otomatis bertambah!');
    }

    public function detail(Pembelian $purchase)
    {
        $purchase->load(['pemasok', 'pengguna', 'itemPembelian.produk']);
        return view('admin.pembelian.detail', compact('purchase'));
    }

    public function index(Request $request)
    {
        return $this->indeks($request);
    }

    public function create()
    {
        return $this->buat();
    }

    public function store(Request $request)
    {
        return $this->simpan($request);
    }

    public function show(Pembelian $purchase)
    {
        return $this->detail($purchase);
    }
}
