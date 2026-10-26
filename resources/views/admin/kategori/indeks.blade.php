@extends('layouts.admin')

@section('title', 'Kategori Menu — Warmindo')
@section('page_title', 'Master Kategori Menu')

@section('content')
<div class="card" style="padding: 0; overflow: hidden;">
  <!-- Header Bar (Screen 11) -->
  <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-light); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
    <div>
      <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark);">Daftar Kategori</h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Pengelompokan menu Warmindo untuk katalog pelanggan</p>
    </div>

    <div style="display: flex; gap: 10px;">
      <form action="{{ route('admin.categories.index') }}" method="GET" style="display: flex; gap: 8px;">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari kategori..." style="width: 200px;">
        <button type="submit" class="btn btn-primary btn-sm">Cari</button>
      </form>
      <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        + Tambah Kategori
      </a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px; width: 60px;">No.</th>
          <th>Nama Kategori</th>
          <th>Slug</th>
          <th style="text-align: center;">Jumlah Produk</th>
          <th>Status</th>
          <th style="text-align: right; padding-right: 24px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($categories as $idx => $cat)
          <tr>
            <td style="padding-left: 24px; color: var(--text-muted);">{{ $categories->firstItem() + $idx }}</td>
            <td style="font-weight: 700; color: var(--text-dark); font-size: 14.5px;">
              {{ $cat->name }}
            </td>
            <td><code style="background: #F1F3F5; padding: 2px 6px; border-radius: 4px;">{{ $cat->slug }}</code></td>
            <td style="text-align: center; font-weight: 700;">
              <span class="badge badge-blue">{{ $cat->products_count }} Menu</span>
            </td>
            <td>
              @if($cat->is_active)
                <span class="badge badge-green"><span class="badge-dot"></span> Aktif</span>
              @else
                <span class="badge badge-red"><span class="badge-dot"></span> Nonaktif</span>
              @endif
            </td>
            <td style="text-align: right; padding-right: 24px;">
              <div style="display: inline-flex; gap: 8px;">
                <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Kategori yang memiliki produk tidak dapat dihapus.');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  @if($categories->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
      <span style="font-size: 13px; color: var(--text-muted);">Total {{ $categories->total() }} kategori</span>
      {{ $categories->links() }}
    </div>
  @endif
</div>
@endsection
