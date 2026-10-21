@extends('layouts.kasir')

@section('title', 'Pesanan Menunggu Pembayaran — Kasir Warmindo')

@section('content')
<div class="card" style="padding: 0; overflow: hidden; margin-bottom: 24px;">
  <!-- Header Bar (Screen 43) -->
  <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-light); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px;">
    <div>
      <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark);">Pesanan Menunggu Pembayaran</h2>
      <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Cocokkan pesanan customer dengan nomor meja atau nomor pesanan lalu proses pembayaran.</p>
    </div>

    <!-- Search Box -->
    <form action="{{ route('kasir.waiting') }}" method="GET" style="display: flex; gap: 10px;">
      <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nomor pesanan / meja..." style="width: 260px;">
      <button type="submit" class="btn btn-primary">Filter</button>
      @if(request('search'))
        <a href="{{ route('kasir.waiting') }}" class="btn btn-secondary">Reset</a>
      @endif
    </form>
  </div>

  <!-- Orders Table (Screen 43) -->
  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px;">No. Pesanan</th>
          <th>Meja</th>
          <th>Menu</th>
          <th>Total</th>
          <th>Waktu Masuk</th>
          <th style="text-align: right; padding-right: 24px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
          <tr>
            <td style="padding-left: 24px; font-weight: 800; color: var(--primary);">
              #{{ $order->sale_number }}
            </td>
            <td>
              @if($order->order_type === 'dine_in')
                <span class="badge badge-blue">Meja {{ $order->table_number }}</span>
              @else
                <span class="badge badge-orange">Take Away</span>
              @endif
            </td>
            <td style="font-size: 13.5px; color: var(--text-dark); max-width: 260px;">
              {{ $order->items_summary }}
            </td>
            <td style="font-weight: 800; color: var(--text-dark); font-size: 14.5px;">
              {{ $order->formatted_total }}
            </td>
            <td style="color: var(--text-muted); font-size: 13px;">
              {{ $order->created_at->format('H:i') }} ({{ $order->created_at->diffForHumans() }})
            </td>
            <td style="text-align: right; padding-right: 24px;">
              <div style="display: inline-flex; gap: 8px;">
                <a href="{{ route('kasir.detail', $order->sale_number) }}" class="btn btn-secondary btn-sm">
                  Detail
                </a>
                <a href="{{ route('kasir.pay', $order->sale_number) }}" class="btn btn-primary btn-sm">
                  Bayar
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align: center; padding: 48px;">
              <div class="state-box" style="padding: 0;">
                <img src="{{ asset('images/empty-box.svg') }}" alt="Tidak Ada Pesanan" class="state-img" style="width: 100px; height: 100px;">
                <h4 class="state-title" style="font-size: 16px;">Tidak ada pesanan menunggu</h4>
                <p class="state-desc" style="font-size: 13px;">Semua pesanan yang masuk sudah diproses dan lunas.</p>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($orders->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
      <span style="font-size: 13px; color: var(--text-muted);">Total {{ $orders->total() }} pesanan</span>
      {{ $orders->links() }}
    </div>
  @endif
</div>
@endsection
