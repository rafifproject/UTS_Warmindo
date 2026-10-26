<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class KontrollerPublik extends Controller
{
    public function beranda()
    {
        $produkFavorit = Produk::with('kategori')
            ->where('is_available', true)
            ->where('is_favorite', true)
            ->take(4)
            ->get();

        if ($produkFavorit->isEmpty()) {
            $produkFavorit = Produk::with('kategori')
                ->where('is_available', true)
                ->take(4)
                ->get();
        }

        return view('publik.beranda', [
            'favorites'     => $produkFavorit,
            'produkFavorit' => $produkFavorit,
        ]);
    }

    public function katalogMenu(Request $request)
    {
        $semuaKategori    = Kategori::where('is_active', true)->get();
        $kategoriTerpilih = $request->query('category', 'all');
        $pencarianMenu    = $request->query('search');

        $query = Produk::with('kategori')->where('is_available', true);

        if ($kategoriTerpilih && $kategoriTerpilih !== 'all') {
            $query->whereHas('kategori', function ($q) use ($kategoriTerpilih) {
                $q->where('slug', $kategoriTerpilih);
            });
        }

        if ($pencarianMenu) {
            $query->where(function ($q) use ($pencarianMenu) {
                $q->where('name', 'like', "%{$pencarianMenu}%")
                  ->orWhere('sku', 'like', "%{$pencarianMenu}%");
            });
        }

        $semuaProduk = $query->paginate(12)->withQueryString();

        return view('publik.katalog-menu', [
            'categories'       => $semuaKategori,
            'selectedCategory' => $kategoriTerpilih,
            'search'           => $pencarianMenu,
            'products'         => $semuaProduk,
        ]);
    }

    public function detailMenu($slug)
    {
        $produk = Produk::with('kategori')
            ->where('slug', $slug)
            ->where('is_available', true)
            ->firstOrFail();

        $produkTerkait = Produk::where('category_id', $produk->category_id)
            ->where('id', '!=', $produk->id)
            ->where('is_available', true)
            ->take(3)
            ->get();

        return view('publik.detail-menu', [
            'product'         => $produk,
            'relatedProducts' => $produkTerkait,
        ]);
    }

    public function home()
    {
        return $this->beranda();
    }

    public function menu(Request $request)
    {
        return $this->katalogMenu($request);
    }

    public function menuDetail($slug)
    {
        return $this->detailMenu($slug);
    }
}
