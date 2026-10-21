@extends('layouts.admin')

@section('title', 'Pembelian & Stok Masuk — Warmindo')
@section('page_title', 'Pembelian & Stok Masuk')

@section('content')
<div class="card" style="padding: 0; overflow: hidden;">
  <!-- Header Bar (Screen 26) -->
  <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-light); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
    <div>
      <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark);">Daftar Transaksi Pembelian</h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Pencatatan pasokan masuk dari supplier. Stok bertambah otomatis secara atomik.</p>
    </div>

    <div style="display: flex; gap: 10px;">
      <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary">
        + Form Pembelian (Banyak Item)
      </a>
    </div>
  </div>

  <!-- Filter Bar -->
  <div style="padding: 16px 24px; background-color: #FCFDFD; border-bottom: 1px solid var(--border-light); display: flex; gap: 12px; flex-wrap: wrap;">
    <form action="{{ route('admin.purchases.index') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
      <select name="supplier_id" class="form-control form-select" style="width: 200px;">
        <option value="all">Semua Supplier</option>
        @foreach($suppliers as $s)
          <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
        @endforeach
      </select>
      <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" style="width: 140px;">
      <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" style="width: 140px;">
      <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="No. Pembelian..." style="width: 180px;">
      <button type="submit" class="btn btn-primary btn-sm">Filter</button>
      @if(request()->hasAny(['supplier_id', 'start_date', 'end_date', 'search']))
        <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary btn-sm">Reset</a>
      @endif
    </form>
  </div>

  <!-- Purchases Table (Screen 26) -->
  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px;">No. Pembelian</th>
          <th>Tanggal</th>
          <th>Nama Supplier</th>
          <th style="text-align: center;">Item</th>
          <th style="text-align: right;">Total Nilai</th>
          <th>Petugas</th>
          <th>Status</th>
          <th style="text-align: right; padding-right: 24px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($purchases as $p)
          <tr>
            <td style="padding-left: 24px; font-weight: 800; color: var(--primary);">
              #{{ $p->purchase_number }}
            </td>
            <td>{{ $p->purchase_date->format('d M Y') }}</td>
            <td style="font-weight: 700; color: var(--text-dark);">
              {{ $p->supplier->name ?? 'Supplier Dihapus' }}
            </td>
            <td style="text-align: center;">
              <span class="badge badge-blue">{{ $p->items->count() }} jenis</span>
            </td>
            <td style="text-align: right; font-weight: 800; color: var(--text-dark);">
              {{ $p->formatted_total }}
            </td>
            <td style="font-size: 13px; color: var(--text-muted);">
              {{ $p->user->name ?? 'Admin' }}
            </td>
            <td>
              <span class="badge badge-green"><span class="badge-dot"></span> Selesai (Stok Masuk)</span>
            </td>
            <td style="text-align: right; padding-right: 24px;">
              <a href="{{ route('admin.purchases.show', $p->id) }}" class="btn btn-secondary btn-sm">
                Lihat Detail
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-muted);">
              Belum ada data transaksi pembelian.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($purchases->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
      <span style="font-size: 13px; color: var(--text-muted);">Menampilkan {{ $purchases->firstItem() }} - {{ $purchases->lastItem() }} dari {{ $purchases->total() }} pembelian</span>
      {{ $purchases->links() }}
    </div>
  @endif
</div>
@endsection
