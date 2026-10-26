@extends('layouts.admin')

@section('title', 'Detail Supplier — ' . $supplier->name)
@section('page_title', 'Detail Supplier & Riwayat Pasokan')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
  <div style="margin-bottom: 20px;">
    <a href="{{ route('admin.suppliers.index') }}" style="font-size: 13.5px; font-weight: 600; color: var(--primary);">
      &larr; Kembali ke Daftar Supplier
    </a>
  </div>

  <!-- Supplier Profile Card (Screen 24) -->
  <div class="card" style="padding: 28px; margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 16px;">
      <div>
        <h2 style="font-size: 22px; font-weight: 800; color: var(--text-dark); margin: 0 0 4px;">{{ $supplier->name }}</h2>
        <p style="font-size: 13px; color: var(--text-muted); margin: 0;">PIC: {{ $supplier->contact_person ?? '-' }}</p>
      </div>

      <div style="display: flex; align-items: center; gap: 10px;">
        @if($supplier->is_active)
          <span class="badge badge-green"><span class="badge-dot"></span> Aktif</span>
        @else
          <span class="badge badge-red"><span class="badge-dot"></span> Nonaktif</span>
        @endif
        <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-secondary btn-sm">Edit</a>
      </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; font-size: 13.5px;">
      <div>
        <span style="color: var(--text-muted); display: block; margin-bottom: 2px;">Telepon / WhatsApp:</span>
        <span style="font-weight: 700; color: var(--text-dark);">{{ $supplier->phone ?? '-' }}</span>
      </div>
      <div>
        <span style="color: var(--text-muted); display: block; margin-bottom: 2px;">Email:</span>
        <span style="font-weight: 700; color: var(--text-dark);">{{ $supplier->email ?? '-' }}</span>
      </div>
      <div>
        <span style="color: var(--text-muted); display: block; margin-bottom: 2px;">Alamat:</span>
        <span style="font-weight: 600; color: var(--text-dark);">{{ $supplier->address ?? '-' }}</span>
      </div>
    </div>
  </div>

  <!-- Riwayat Pembelian dari Supplier Ini (Screen 24) -->
  <div class="card" style="padding: 0; overflow: hidden;">
    <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
      <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark);">Riwayat Pembelian Barang</h3>
      <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary btn-sm">+ Beli Stok Masuk</a>
    </div>

    <div class="table-responsive">
      <table class="table" style="margin: 0;">
        <thead>
          <tr>
            <th style="padding-left: 24px;">No. Pembelian</th>
            <th>Tanggal</th>
            <th>Rincian Item</th>
            <th style="text-align: right;">Total Nilai</th>
            <th>Status</th>
            <th style="text-align: right; padding-right: 24px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse(($supplier->purchases ?? $supplier->pembelian ?? []) as $p)
            <tr>
              <td style="padding-left: 24px; font-weight: 700; color: var(--primary);">
                #{{ $p->purchase_number }}
              </td>
              <td>{{ $p->purchase_date->format('d M Y') }}</td>
              <td style="font-size: 13px; color: var(--text-body);">
                {{ $p->items->count() }} item ({{ $p->items->sum('quantity') }} porsi)
              </td>
              <td style="text-align: right; font-weight: 800; color: var(--text-dark);">
                {{ $p->formatted_total }}
              </td>
              <td>
                <span class="badge badge-green">Selesai</span>
              </td>
              <td style="text-align: right; padding-right: 24px;">
                <a href="{{ route('admin.purchases.show', $p->id) }}" class="btn btn-secondary btn-sm">Lihat Detail</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align: center; padding: 30px; color: var(--text-muted);">
                Belum ada transaksi pembelian dari supplier ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
