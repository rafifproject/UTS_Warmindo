<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\MutasiStok;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KontrollerProduk extends Controller
{
    public function indeks(Request $request)
    {
        $semuaKategori = Kategori::all();
        $query = Produk::with('kategori');

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $cari = $request->search;
            $query->where(function ($q) use ($cari) {
                $q->where('name', 'like', "%{$cari}%")
                  ->orWhere('sku', 'like', "%{$cari}%");
            });
        }

        $semuaProduk = $query->latest()->paginate(10)->withQueryString();
        return view('admin.produk.indeks', [
            'products'   => $semuaProduk,
            'categories' => $semuaKategori,
        ]);
    }

    public function buat()
    {
        $semuaKategori = Kategori::where('is_active', true)->get();
        return view('admin.produk.buat', ['categories' => $semuaKategori]);
    }

    public function simpan(Request $request)
    {
        $validated = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'sku'          => 'required|string|max:50|unique:products,sku',
            'name'         => 'required|string|max:255',
            'buy_price'    => 'required|numeric|min:0',
            'sell_price'   => 'required|numeric|min:1000',
            'stock'        => 'required|integer|min:0',
            'min_stock'    => 'required|integer|min:0',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|max:2048',
            'is_available' => 'boolean',
            'is_favorite'  => 'boolean',
        ]);

        $validated['slug']         = Str::slug($validated['name']) . '-' . Str::lower(Str::random(4));
        $validated['is_available'] = $request->has('is_available');
        $validated['is_favorite']  = $request->has('is_favorite');

        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/menu'), $filename);
            $validated['image'] = 'images/menu/' . $filename;
        }

        $produk = Produk::create($validated);

        if ($produk->stock > 0) {
            MutasiStok::create([
                'product_id'       => $produk->id,
                'type'             => 'in',
                'reference_type'   => 'adjustment',
                'reference_id'     => null,
                'reference_number' => 'STOK-AWAL',
                'quantity'         => $produk->stock,
                'before_stock'     => 0,
                'after_stock'      => $produk->stock,
                'notes'            => 'Stok awal produk baru',
                'user_id'          => auth()->id(),
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk baru berhasil ditambahkan.');
    }

    public function detail(Produk $product)
    {
        $product->load(['kategori', 'mutasiStok.pengguna']);
        return view('admin.produk.detail', compact('product'));
    }

    public function ubah(Produk $product)
    {
        $semuaKategori = Kategori::where('is_active', true)->get();
        return view('admin.produk.ubah', ['product' => $product, 'categories' => $semuaKategori]);
    }

    public function perbarui(Request $request, Produk $product)
    {
        $validated = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'sku'          => ['required', 'string', 'max:50', Rule::unique('products')->ignore($product->id)],
            'name'         => 'required|string|max:255',
            'buy_price'    => 'required|numeric|min:0',
            'sell_price'   => 'required|numeric|min:1000',
            'stock'        => 'required|integer|min:0',
            'min_stock'    => 'required|integer|min:0',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|max:2048',
            'is_available' => 'boolean',
            'is_favorite'  => 'boolean',
        ]);

        $validated['is_available'] = $request->has('is_available');
        $validated['is_favorite']  = $request->has('is_favorite');

        $stokLama = $product->stock;
        $stokBaru = (int)$validated['stock'];

        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/menu'), $filename);
            $validated['image'] = 'images/menu/' . $filename;
        }

        $product->update($validated);

        if ($stokLama !== $stokBaru) {
            MutasiStok::create([
                'product_id'       => $product->id,
                'type'             => $stokBaru > $stokLama ? 'in' : 'out',
                'reference_type'   => 'adjustment',
                'reference_id'     => null,
                'reference_number' => 'PENYESUAIAN',
                'quantity'         => abs($stokBaru - $stokLama),
                'before_stock'     => $stokLama,
                'after_stock'      => $stokBaru,
                'notes'            => 'Penyesuaian manual oleh ' . (auth()->user()->name ?? 'Admin'),
                'user_id'          => auth()->id(),
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function hapus(Produk $product)
    {
        $namaProduk = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk "' . $namaProduk . '" berhasil dinonaktifkan/dihapus.');
    }

    public function stokMenipis()
    {
        $semuaProduk = Produk::with('kategori')
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->paginate(15);

        return view('admin.produk.stok-menipis', ['products' => $semuaProduk]);
    }

    public function mutasiStok(Request $request)
    {
        $query = MutasiStok::with(['produk', 'pengguna'])->latest();

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $cari = $request->search;
            $query->whereHas('produk', function ($pq) use ($cari) {
                $pq->where('name', 'like', "%{$cari}%")->orWhere('sku', 'like', "%{$cari}%");
            });
        }

        $semuaMutasi = $query->paginate(15)->withQueryString();

        return view('admin.produk.mutasi-stok', ['mutations' => $semuaMutasi]);
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

    public function show(Produk $product)
    {
        return $this->detail($product);
    }

    public function edit(Produk $product)
    {
        return $this->ubah($product);
    }

    public function update(Request $request, Produk $product)
    {
        return $this->perbarui($request, $product);
    }

    public function destroy(Produk $product)
    {
        return $this->hapus($product);
    }

    public function lowStock()
    {
        return $this->stokMenipis();
    }

    public function stockMutations(Request $request)
    {
        return $this->mutasiStok($request);
    }
}
