@extends('layouts.kasir')

@section('title', 'Dashboard Kasir — Warmindo')

@section('content')
<!-- Stat Cards (Screen 42) -->
<div class="stat-grid" style="margin-bottom: 24px;">
  <div class="stat-card">
    <div>
      <div class="stat-label">Pesanan Menunggu</div>
      <div class="stat-value" style="color: var(--primary);">{{ $waitingCount }}</div>
    </div>
    <div class="stat-icon orange">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Transaksi Hari Ini</div>
      <div class="stat-value">{{ $todayTransactionsCount }}</div>
    </div>
    <div class="stat-icon green">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Total Penjualan Hari Ini</div>
      <div class="stat-value" style="color: #0CA678;">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="stat-icon blue">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    </div>
  </div>
</div>

<div style="display: flex; flex-wrap: wrap; gap: 24px; align-items: flex-start;">
  <!-- Left: Pesanan Terbaru Table (Screen 42) -->
  <div style="flex: 2; min-width: 320px;">
    <div class="card" style="padding: 0; overflow: hidden;">
      <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark);">Pesanan Terbaru</h3>
        <a href="{{ route('kasir.waiting') }}" style="font-size: 13px; font-weight: 700; color: var(--primary);">Lihat Semua &rarr;</a>
      </div>

      <div class="table-responsive">
        <table class="table" style="margin: 0;">
          <thead>
            <tr>
              <th style="padding-left: 24px;">No. Pesanan</th>
              <th>Meja / Layanan</th>
              <th>Menu Utama</th>
              <th>Total</th>
              <th>Status</th>
              <th style="text-align: right; padding-right: 24px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentOrders as $order)
              <tr>
                <td style="padding-left: 24px; font-weight: 700; color: var(--primary);">
                  #{{ $order->sale_number }}
                </td>
                <td>
                  @if($order->order_type === 'dine_in')
                    <span style="font-weight: 600;">Meja {{ $order->table_number }}</span>
                  @else
                    <span style="color: var(--text-muted);">Take Away</span>
                  @endif
                </td>
                <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                  {{ $order->items_summary }}
                </td>
                <td style="font-weight: 700;">
                  {{ $order->formatted_total }}
                </td>
                <td>
                  @if($order->payment_status === 'pending')
                    <span class="badge badge-orange"><span class="badge-dot"></span> Menunggu</span>
                  @elseif($order->payment_status === 'paid')
                    <span class="badge badge-green"><span class="badge-dot"></span> Lunas</span>
                  @else
                    <span class="badge badge-red">Dibatalkan</span>
                  @endif
                </td>
                <td style="text-align: right; padding-right: 24px;">
                  @if($order->payment_status === 'pending')
                    <a href="{{ route('kasir.pay', $order->sale_number) }}" class="btn btn-primary btn-sm">
                      Bayar
                    </a>
                  @else
                    <a href="{{ route('kasir.receipt', $order->sale_number) }}" class="btn btn-secondary btn-sm">
                      Struk
                    </a>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" style="text-align: center; padding: 30px; color: var(--text-muted);">
                  Belum ada pesanan masuk saat ini.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Right: Pesanan Cepat Box (Screen 42) -->
  <div style="flex: 1; min-width: 280px;">
    <div class="card">
      <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        Pencarian Cepat
      </h3>

      <!-- Search by Order Number -->
      <form action="{{ route('kasir.search') }}" method="GET" style="margin-bottom: 20px;">
        <div class="form-group" style="margin-bottom: 8px;">
          <label class="form-label" for="order_no_search">Nomor Pesanan</label>
          <div style="display: flex; gap: 8px;">
            <input type="text" name="q" id="order_no_search" class="form-control" placeholder="Contoh: PSN-0012">
            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
          </div>
        </div>
      </form>

      <!-- Search by Table Number -->
      <form action="{{ route('kasir.search') }}" method="GET" style="margin-bottom: 24px;">
        <input type="hidden" name="tab" value="dine_in">
        <div class="form-group" style="margin-bottom: 8px;">
          <label class="form-label" for="table_no_search">Nomor Meja</label>
          <div style="display: flex; gap: 8px;">
            <input type="text" name="q" id="table_no_search" class="form-control" placeholder="Contoh: Meja 3">
            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
          </div>
        </div>
      </form>

      <a href="{{ route('kasir.waiting') }}" class="btn btn-secondary btn-block">
        Lihat Semua Pesanan Menunggu
      </a>
    </div>
  </div>
</div>
@endsection
