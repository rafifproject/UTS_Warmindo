@extends('layouts.admin')

@section('title', 'Daftar Stok Menipis — Warmindo')
@section('page_title', 'Peringatan Stok Menipis')

@section('content')
<div class="card" style="padding: 0; overflow: hidden;">
  <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
    <div>
      <h3 style="font-size: 16px; font-weight: 800; color: #E03131; display: flex; align-items: center; gap: 8px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        Daftar Produk Stok Menipis (Di Bawah Batas Minimum)
      </h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Produk yang membutuhkan pasokan stok masuk segera dari supplier.</p>
    </div>

    <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary">
      + Buat Pembelian Stok Masuk
    </a>
  </div>

  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px;">No.</th>
          <th>Nama Menu</th>
          <th>Kategori</th>
          <th style="text-align: center;">Stok Saat Ini</th>
          <th style="text-align: center;">Batas Minimum</th>
          <th>Status</th>
          <th style="text-align: right; padding-right: 24px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $idx => $prod)
          <tr>
            <td style="padding-left: 24px; color: var(--text-muted);">{{ $idx + 1 }}</td>
            <td>
              <div style="font-weight: 700; color: var(--text-dark);">{{ $prod->name }}</div>
              <div style="font-size: 11.5px; color: var(--text-muted);">SKU: {{ $prod->sku }}</div>
            </td>
            <td><span class="badge badge-blue">{{ $prod->category->name ?? '-' }}</span></td>
            <td style="text-align: center; font-size: 16px; font-weight: 800; color: #FA5252;">
              {{ $prod->stock }}
            </td>
            <td style="text-align: center; color: var(--text-muted); font-weight: 600;">
              {{ $prod->min_stock }}
            </td>
            <td>
              <span class="badge badge-red">Kritis</span>
            </td>
            <td style="text-align: right; padding-right: 24px;">
              <a href="{{ route('admin.products.edit', $prod->id) }}" class="btn btn-secondary btn-sm">Edit</a>
              <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary btn-sm">Restock</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align: center; padding: 40px; color: #0CA678; font-weight: 700;">
              &check; Luar biasa! Seluruh stok produk Warmindo berada di atas batas minimum.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
