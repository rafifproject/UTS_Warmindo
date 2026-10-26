<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemasok;
use Illuminate\Http\Request;

class KontrollerPemasok extends Controller
{
    public function indeks(Request $request)
    {
        $query = Pemasok::withCount('pembelian');

        if ($request->filled('search')) {
            $cari = $request->search;
            $query->where(function ($q) use ($cari) {
                $q->where('name', 'like', "%{$cari}%")
                  ->orWhere('contact_person', 'like', "%{$cari}%")
                  ->orWhere('phone', 'like', "%{$cari}%");
            });
        }

        $semuaPemasok = $query->latest()->paginate(10)->withQueryString();
        return view('admin.pemasok.indeks', ['suppliers' => $semuaPemasok]);
    }

    public function buat()
    {
        return view('admin.pemasok.buat');
    }

    public function simpan(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:100',
            'address'        => 'nullable|string',
            'is_active'      => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        Pemasok::create($validated);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function detail(Pemasok $supplier)
    {
        $supplier->load(['pembelian.itemPembelian.produk']);
        return view('admin.pemasok.detail', compact('supplier'));
    }

    public function ubah(Pemasok $supplier)
    {
        return view('admin.pemasok.ubah', compact('supplier'));
    }

    public function perbarui(Request $request, Pemasok $supplier)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:100',
            'address'        => 'nullable|string',
            'is_active'      => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $supplier->update($validated);

        return redirect()->route('admin.suppliers.index')->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function hapus(Pemasok $supplier)
    {
        if ($supplier->pembelian()->count() > 0) {
            return back()->with('error', 'Supplier tidak dapat dihapus karena memiliki riwayat transaksi pembelian.');
        }

        $supplier->delete();
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil dihapus.');
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

    public function show(Pemasok $supplier)
    {
        return $this->detail($supplier);
    }

    public function edit(Pemasok $supplier)
    {
        return $this->ubah($supplier);
    }

    public function update(Request $request, Pemasok $supplier)
    {
        return $this->perbarui($request, $supplier);
    }

    public function destroy(Pemasok $supplier)
    {
        return $this->hapus($supplier);
    }
}
