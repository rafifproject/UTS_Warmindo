<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KontrollerKategori extends Controller
{
    public function indeks(Request $request)
    {
        $query = Kategori::withCount('produk');

        if ($request->filled('search')) {
            $cari = $request->search;
            $query->where('name', 'like', "%{$cari}%")
                  ->orWhere('description', 'like', "%{$cari}%");
        }

        $semuaKategori = $query->paginate(10)->withQueryString();
        return view('admin.kategori.indeks', ['categories' => $semuaKategori]);
    }

    public function buat()
    {
        return view('admin.kategori.buat');
    }

    public function simpan(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $validated['slug']      = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        Kategori::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function ubah(Kategori $category)
    {
        return view('admin.kategori.ubah', ['category' => $category]);
    }

    public function perbarui(Request $request, Kategori $category)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100', Rule::unique('categories')->ignore($category->id)],
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $validated['slug']      = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function hapus(Kategori $category)
    {
        if ($category->produk()->count() > 0) {
            return back()->with('error', 'Kategori "' . $category->name . '" tidak dapat dihapus karena masih memiliki ' . $category->produk()->count() . ' produk.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
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

    public function edit(Kategori $category)
    {
        return $this->ubah($category);
    }

    public function update(Request $request, Kategori $category)
    {
        return $this->perbarui($request, $category);
    }

    public function destroy(Kategori $category)
    {
        return $this->hapus($category);
    }
}
