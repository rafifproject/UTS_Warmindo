@extends('layouts.admin')

@section('title', 'Riwayat Pergerakan Stok — Warmindo')
@section('page_title', 'Audit Riwayat Pergerakan Stok')

@section('content')
<div class="card" style="padding: 0; overflow: hidden;">
  <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-light); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
    <div>
      <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark);">Log Mutasi Persediaan</h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Catatan lengkap barang masuk (pembelian), keluar (penjualan kasir), dan penyesuaian manual.</p>
    </div>

    <form action="{{ route('admin.products.stock-mutations') }}" method="GET" style="display: flex; gap: 8px;">
      <select name="type" class="form-control form-select" style="width: 140px;">
        <option value="all">Semua Tipe</option>
        <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Stok Masuk</option>
        <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Stok Keluar</option>
        <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Penyesuaian</option>
      </select>
      <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari produk / SKU..." style="width: 200px;">
      <button type="submit" class="btn btn-primary btn-sm">Filter</button>
      @if(request()->hasAny(['type', 'search']))
        <a href="{{ route('admin.products.stock-mutations') }}" class="btn btn-secondary btn-sm">Reset</a>
      @endif
    </form>
  </div>

  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px;">Waktu</th>
          <th>Nama Menu</th>
          <th>Tipe Mutasi</th>
          <th>No. Referensi</th>
          <th style="text-align: center;">Perubahan</th>
          <th style="text-align: center;">Sebelum</th>
          <th style="text-align: center;">Sesudah</th>
          <th>Keterangan / Petugas</th>
        </tr>
      </thead>
      <tbody>
        @forelse($mutations as $m)
          <tr>
            <td style="padding-left: 24px; font-size: 12.5px; color: var(--text-muted);">
              {{ $m->created_at->format('d/m/Y H:i') }}
            </td>
            <td style="font-weight: 700; color: var(--text-dark);">
              {{ $m->product->name ?? 'Produk Dihapus' }}
            </td>
            <td>
              @if($m->type === 'in')
                <span class="badge badge-green">Masuk</span>
              @elseif($m->type === 'out')
                <span class="badge badge-orange">Keluar</span>
              @else
                <span class="badge badge-blue">Penyesuaian</span>
              @endif
            </td>
            <td><code>{{ $m->reference_number ?? '-' }}</code></td>
            <td style="text-align: center; font-weight: 800; color: {{ $m->type === 'in' ? '#0CA678' : '#FA5252' }};">
              {{ $m->type === 'in' ? '+' : '-' }}{{ $m->quantity }}
            </td>
            <td style="text-align: center; color: var(--text-muted);">{{ $m->before_stock }}</td>
            <td style="text-align: center; font-weight: 700; color: var(--text-dark);">{{ $m->after_stock }}</td>
            <td style="font-size: 13px; color: var(--text-body);">
              {{ $m->notes }} <span style="color: var(--text-muted);">({{ $m->user->name ?? 'Sistem' }})</span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-muted);">
              Belum ada riwayat mutasi stok.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($mutations->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
      <span style="font-size: 13px; color: var(--text-muted);">Menampilkan {{ $mutations->firstItem() }} - {{ $mutations->lastItem() }} dari {{ $mutations->total() }} mutasi</span>
      {{ $mutations->links() }}
    </div>
  @endif
</div>
@endsection
