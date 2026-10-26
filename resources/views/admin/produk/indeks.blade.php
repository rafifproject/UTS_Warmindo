@extends('layouts.admin')

@section('title', 'Produk & Persediaan — Warmindo')
@section('page_title', 'Master Produk & Persediaan Menu')

@section('content')
<div class="card" style="padding: 0; overflow: hidden; margin-bottom: 24px;">
  <!-- Header Bar (Screen 15) -->
  <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-light); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
    <div>
      <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark);">Daftar Produk Menu</h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Kelola master menu, harga beli, harga jual, dan batas stok minimum</p>
    </div>

    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
      <a href="{{ route('admin.products.low-stock') }}" class="btn btn-secondary btn-sm" style="color: #FA5252; font-weight: 700;">
        ⚠️ Stok Menipis
      </a>
      <a href="{{ route('admin.products.stock-mutations') }}" class="btn btn-secondary btn-sm">
        Riwayat Mutasi Stok
      </a>
      <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        + Tambah Produk
      </a>
    </div>
  </div>

  <!-- Filter & Search Toolbar -->
  <div style="padding: 16px 24px; background-color: #FCFDFD; border-bottom: 1px solid var(--border-light); display: flex; gap: 12px; flex-wrap: wrap;">
    <form action="{{ route('admin.products.index') }}" method="GET" style="display: flex; gap: 10px; flex: 1; max-width: 600px;">
      <select name="category" class="form-control form-select" style="width: 180px;">
        <option value="all">Semua Kategori</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
      <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama menu atau SKU..." style="flex: 1;">
      <button type="submit" class="btn btn-primary btn-sm">Cari</button>
      @if(request()->hasAny(['category', 'search']))
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">Reset</a>
      @endif
    </form>
  </div>

  <!-- Table (Screen 15) -->
  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px;">No.</th>
          <th>Nama Menu</th>
          <th>Kategori</th>
          <th style="text-align: right;">Harga Jual</th>
          <th style="text-align: center;">Stok</th>
          <th>Status</th>
          <th style="text-align: right; padding-right: 24px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $idx => $prod)
          <tr>
            <td style="padding-left: 24px; color: var(--text-muted); font-size: 13px;">{{ $products->firstItem() + $idx }}</td>
            <td>
              <div style="display: flex; align-items: center; gap: 12px;">
                <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" style="width: 44px; height: 44px; border-radius: var(--radius-md); object-fit: cover; border: 1px solid var(--border-light);">
                <div>
                  <a href="{{ route('admin.products.show', $prod->id) }}" style="font-weight: 700; color: var(--text-dark); font-size: 14.5px;">
                    {{ $prod->name }}
                  </a>
                  <div style="font-size: 11.5px; color: var(--text-muted);">SKU: {{ $prod->sku }}</div>
                </div>
              </div>
            </td>
            <td>
              <span class="badge badge-blue">{{ $prod->category->name ?? '-' }}</span>
            </td>
            <td style="text-align: right; font-weight: 700; color: var(--primary);">
              {{ $prod->formatted_sell_price }}
            </td>
            <td style="text-align: center;">
              @if($prod->stock <= $prod->min_stock)
                <span class="badge badge-red" title="Mendekati batas minimum ({{ $prod->min_stock }})">
                  {{ $prod->stock }} (Menipis)
                </span>
              @else
                <span style="font-weight: 700; color: var(--text-dark);">{{ $prod->stock }}</span>
              @endif
            </td>
            <td>
              @if($prod->is_available)
                <span class="badge badge-green"><span class="badge-dot"></span> Aktif</span>
              @else
                <span class="badge badge-red"><span class="badge-dot"></span> Habis</span>
              @endif
            </td>
            <td style="text-align: right; padding-right: 24px;">
              <div style="display: inline-flex; gap: 6px;">
                <a href="{{ route('admin.products.show', $prod->id) }}" class="btn btn-secondary btn-sm" title="Detail">
                  Lihat
                </a>
                <a href="{{ route('admin.products.edit', $prod->id) }}" class="btn btn-secondary btn-sm" title="Edit">
                  Edit
                </a>
                <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan produk ini? Riwayat transaksi lama tetap aman tersimpan.');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm" title="Hapus Aman">
                    Hapus
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align: center; padding: 48px;">
              <div class="state-box" style="padding: 0;">
                <img src="{{ asset('images/empty-box.svg') }}" alt="Produk Kosong" class="state-img" style="width: 100px; height: 100px;">
                <h4 class="state-title" style="font-size: 16px;">Belum ada data produk</h4>
                <p class="state-desc" style="font-size: 13px;">Tambahkan menu Warmindo untuk mulai berjualan.</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Tambah Produk</a>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($products->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
      <span style="font-size: 13px; color: var(--text-muted);">Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk</span>
      {{ $products->links() }}
    </div>
  @endif
</div>
@endsection
