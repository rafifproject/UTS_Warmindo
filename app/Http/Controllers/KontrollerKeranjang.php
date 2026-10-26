<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\ItemPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KontrollerKeranjang extends Controller
{
    public function indeks()
    {
        $keranjang = session()->get('cart', []);
        $subtotal  = 0;

        foreach ($keranjang as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $biayaLayanan = 0;
        $total        = $subtotal + $biayaLayanan;

        return view('publik.keranjang', [
            'cart'       => $keranjang,
            'subtotal'   => $subtotal,
            'serviceFee' => $biayaLayanan,
            'total'      => $total,
        ]);
    }

    public function tambahMenu(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        if (!$produk->is_available || $produk->stock <= 0) {
            return back()->with('error', 'Maaf, menu "' . $produk->name . '" saat ini sedang tidak tersedia atau stok habis.');
        }

        $jumlah    = max(1, (int)$request->input('quantity', 1));
        $keranjang = session()->get('cart', []);

        if (isset($keranjang[$id])) {
            $jumlahBaru = $keranjang[$id]['quantity'] + $jumlah;
            if ($jumlahBaru > $produk->stock) {
                return back()->with('error', 'Stok tidak mencukupi. Stok ' . $produk->name . ' hanya tersisa ' . $produk->stock . '.');
            }
            $keranjang[$id]['quantity'] = $jumlahBaru;
        } else {
            if ($jumlah > $produk->stock) {
                return back()->with('error', 'Stok tidak mencukupi. Stok ' . $produk->name . ' hanya tersisa ' . $produk->stock . '.');
            }
            $keranjang[$id] = [
                'id'       => $produk->id,
                'name'     => $produk->name,
                'price'    => $produk->sell_price,
                'quantity' => $jumlah,
                'image'    => $produk->image_url,
                'sku'      => $produk->sku,
            ];
        }

        session()->put('cart', $keranjang);

        if ($request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'cartCount' => array_sum(array_column($keranjang, 'quantity')),
                'message'   => 'Menu berhasil ditambahkan ke keranjang!',
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }

    public function perbaruiMenu(Request $request, $id)
    {
        $produk    = Produk::findOrFail($id);
        $jumlah    = (int)$request->input('quantity', 1);
        $keranjang = session()->get('cart', []);

        if ($jumlah <= 0) {
            unset($keranjang[$id]);
            session()->put('cart', $keranjang);
            return back()->with('success', 'Menu dihapus dari keranjang.');
        }

        if ($jumlah > $produk->stock) {
            return back()->with('error', 'Stok tidak mencukupi! Stok ' . $produk->name . ' hanya tersisa ' . $produk->stock . '.');
        }

        if (isset($keranjang[$id])) {
            $keranjang[$id]['quantity'] = $jumlah;
            session()->put('cart', $keranjang);
        }

        return back()->with('success', 'Jumlah menu diperbarui.');
    }

    public function hapusMenu($id)
    {
        $keranjang = session()->get('cart', []);
        if (isset($keranjang[$id])) {
            unset($keranjang[$id]);
            session()->put('cart', $keranjang);
        }
        return back()->with('success', 'Menu berhasil dihapus dari keranjang.');
    }

    public function pilihanLayanan()
    {
        $keranjang = session()->get('cart', []);
        if (empty($keranjang)) {
            return redirect()->route('menu')->with('error', 'Keranjang Anda masih kosong. Silakan pilih menu terlebih dahulu.');
        }

        $dataLayanan = session()->get('checkout_service', [
            'order_type'   => 'dine_in',
            'table_number' => '',
        ]);

        return view('publik.pilihan-layanan', ['serviceData' => $dataLayanan]);
    }

    public function simpanLayanan(Request $request)
    {
        $validated = $request->validate([
            'order_type'   => 'required|in:dine_in,take_away',
            'table_number' => 'nullable|string|max:10',
        ]);

        if ($validated['order_type'] === 'dine_in' && empty(trim($validated['table_number'] ?? ''))) {
            return back()->withErrors(['table_number' => 'Nomor meja wajib diisi untuk pesanan makan di tempat (Dine In).'])->withInput();
        }

        if ($validated['order_type'] === 'take_away') {
            $validated['table_number'] = null;
        }

        session()->put('checkout_service', $validated);

        return redirect()->route('cart.checkout');
    }

    public function konfirmasiPesanan()
    {
        $keranjang = session()->get('cart', []);
        if (empty($keranjang)) {
            return redirect()->route('menu')->with('error', 'Keranjang Anda masih kosong.');
        }

        $layanan = session()->get('checkout_service', [
            'order_type'   => 'dine_in',
            'table_number' => '01',
        ]);

        $subtotal = 0;
        foreach ($keranjang as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $biayaLayanan = 0;
        $total        = $subtotal + $biayaLayanan;

        return view('publik.konfirmasi-pesanan', [
            'cart'       => $keranjang,
            'service'    => $layanan,
            'subtotal'   => $subtotal,
            'serviceFee' => $biayaLayanan,
            'total'      => $total,
        ]);
    }

    public function simpanPesanan(Request $request)
    {
        $keranjang = session()->get('cart', []);
        if (empty($keranjang)) {
            return redirect()->route('menu')->with('error', 'Keranjang kosong.');
        }

        $layanan = session()->get('checkout_service', [
            'order_type'   => 'dine_in',
            'table_number' => null,
        ]);

        if ($layanan['order_type'] === 'dine_in' && empty($layanan['table_number'])) {
            return redirect()->route('cart.service')->withErrors(['table_number' => 'Nomor meja wajib diisi untuk Dine In.']);
        }

        $catatan = $request->input('notes');

        $penjualan = DB::transaction(function () use ($keranjang, $layanan, $catatan) {
            $subtotal   = 0;
            $daftarItem = [];

            foreach ($keranjang as $productId => $itemKeranjang) {
                $produk = Produk::lockForUpdate()->find($productId);

                if (!$produk || !$produk->is_available) {
                    throw new \Exception('Menu "' . $itemKeranjang['name'] . '" sudah tidak tersedia.');
                }

                if ($produk->stock < $itemKeranjang['quantity']) {
                    throw new \Exception('Stok tidak mencukupi untuk "' . $produk->name . '". Stok tersedia hanya ' . $produk->stock . '.');
                }

                $subtotalItem = $produk->sell_price * $itemKeranjang['quantity'];
                $subtotal    += $subtotalItem;

                $daftarItem[] = [
                    'product_id'   => $produk->id,
                    'product_name' => $produk->name,
                    'price'        => $produk->sell_price,
                    'quantity'     => $itemKeranjang['quantity'],
                    'subtotal'     => $subtotalItem,
                    'status'       => 'pending',
                ];
            }

            $lastId      = Penjualan::max('id') ?? 0;
            $nomorPesanan = 'PSN-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

            $penjualan = Penjualan::create([
                'sale_number'    => $nomorPesanan,
                'user_id'        => null,
                'customer_name'  => $layanan['order_type'] === 'dine_in' ? 'Meja ' . $layanan['table_number'] : 'Take Away',
                'order_type'     => $layanan['order_type'],
                'table_number'   => $layanan['order_type'] === 'dine_in' ? $layanan['table_number'] : null,
                'subtotal'       => $subtotal,
                'service_fee'    => 0,
                'total_amount'   => $subtotal,
                'payment_status' => 'pending',
                'order_status'   => 'waiting_payment',
                'notes'          => $catatan,
            ]);

            foreach ($daftarItem as $item) {
                $item['sale_id'] = $penjualan->id;
                ItemPenjualan::create($item);
            }

            return $penjualan;
        });

        session()->forget(['cart', 'checkout_service']);

        return redirect()->route('cart.success', $penjualan->sale_number)->with('success', 'Pesanan Anda berhasil dibuat!');
    }

    public function pesananBerhasil($nomorPesanan)
    {
        $penjualan = Penjualan::with('itemPenjualan.produk')->where('sale_number', $nomorPesanan)->firstOrFail();
        return view('publik.pesanan-berhasil', ['sale' => $penjualan]);
    }

    public function index()
    {
        return $this->indeks();
    }

    public function add(Request $request, $id)
    {
        return $this->tambahMenu($request, $id);
    }

    public function update(Request $request, $id)
    {
        return $this->perbaruiMenu($request, $id);
    }

    public function remove($id)
    {
        return $this->hapusMenu($id);
    }

    public function service()
    {
        return $this->pilihanLayanan();
    }

    public function saveService(Request $request)
    {
        return $this->simpanLayanan($request);
    }

    public function checkout()
    {
        return $this->konfirmasiPesanan();
    }

    public function storeOrder(Request $request)
    {
        return $this->simpanPesanan($request);
    }

    public function success($saleNumber)
    {
        return $this->pesananBerhasil($saleNumber);
    }
}
