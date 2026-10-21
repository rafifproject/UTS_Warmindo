@extends('layouts.admin')

@section('title', 'Penjualan & Transaksi — Warmindo')
@section('page_title', 'Penjualan & Transaksi')

@section('content')
<div class="card" style="padding: 0; overflow: hidden;">
  <!-- Header Bar (Screen 30) -->
  <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-light); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
    <div>
      <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark);">Daftar Transaksi Penjualan</h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Seluruh riwayat pesanan online pelanggan dan pembayaran kasir</p>
    </div>

    <a href="{{ route('admin.reports.export', ['type' => 'sales']) }}" class="btn btn-secondary btn-sm">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Ekspor CSV
    </a>
  </div>

  <!-- Filter Bar (Screen 30) -->
  <div style="padding: 16px 24px; background-color: #FCFDFD; border-bottom: 1px solid var(--border-light); display: flex; gap: 12px; flex-wrap: wrap;">
    <form action="{{ route('admin.sales.index') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
      <select name="payment_status" class="form-control form-select" style="width: 170px;">
        <option value="all">Semua Status Bayar</option>
        <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
        <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Menunggu Bayar</option>
        <option value="cancelled" {{ request('payment_status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
      </select>

      <select name="order_type" class="form-control form-select" style="width: 150px;">
        <option value="all">Semua Layanan</option>
        <option value="dine_in" {{ request('order_type') === 'dine_in' ? 'selected' : '' }}>Dine In</option>
        <option value="take_away" {{ request('order_type') === 'take_away' ? 'selected' : '' }}>Take Away</option>
      </select>

      <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" style="width: 140px;">
      <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" style="width: 140px;">
      <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="No. Pesanan / Meja..." style="width: 180px;">

      <button type="submit" class="btn btn-primary btn-sm">Filter</button>
      @if(request()->hasAny(['payment_status', 'order_type', 'start_date', 'end_date', 'search']))
        <a href="{{ route('admin.sales.index') }}" class="btn btn-secondary btn-sm">Reset</a>
      @endif
    </form>
  </div>

  <!-- Table (Screen 30) -->
  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px;">No. Pesanan</th>
          <th>Tanggal &amp; Waktu</th>
          <th>Layanan</th>
          <th>Menu Dipesan</th>
          <th style="text-align: right;">Total Nilai</th>
          <th>Metode Bayar</th>
          <th>Status</th>
          <th style="text-align: right; padding-right: 24px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($sales as $s)
          <tr>
            <td style="padding-left: 24px; font-weight: 800; color: var(--primary);">
              #{{ $s->sale_number }}
            </td>
            <td style="font-size: 13px; color: var(--text-dark);">
              {{ $s->created_at->format('d/m/Y H:i') }}
            </td>
            <td>
              @if($s->order_type === 'dine_in')
                <span class="badge badge-blue">Dine In (Meja {{ $s->table_number }})</span>
              @else
                <span class="badge badge-orange">Take Away</span>
              @endif
            </td>
            <td style="max-width: 240px; font-weight: 600;">
              {{ $s->items_summary }}
            </td>
            <td style="text-align: right; font-weight: 800; color: var(--text-dark);">
              {{ $s->formatted_total }}
            </td>
            <td>
              <span style="font-size: 12px; text-transform: uppercase; font-weight: 600; color: var(--text-muted);">
                {{ $s->payment_method ?? 'TUNAI' }}
              </span>
            </td>
            <td>
              @if($s->payment_status === 'paid')
                <span class="badge badge-green"><span class="badge-dot"></span> Lunas</span>
              @elseif($s->payment_status === 'pending')
                <span class="badge badge-orange"><span class="badge-dot"></span> Menunggu</span>
              @else
                <span class="badge badge-red">Dibatalkan</span>
              @endif
            </td>
            <td style="text-align: right; padding-right: 24px;">
              <a href="{{ route('admin.sales.show', $s->id) }}" class="btn btn-secondary btn-sm">
                Detail
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-muted);">
              Tidak ada data penjualan yang cocok dengan filter.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($sales->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
      <span style="font-size: 13px; color: var(--text-muted);">Menampilkan {{ $sales->firstItem() }} - {{ $sales->lastItem() }} dari {{ $sales->total() }} transaksi</span>
      {{ $sales->links() }}
    </div>
  @endif
</div>
@endsection
