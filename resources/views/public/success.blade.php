@extends('layouts.public')

@section('title', 'Pesanan Berhasil Dibuat — #' . $sale->sale_number)

@section('content')
<div class="container" style="padding-top: 36px; max-width: 920px;">
  <!-- Success Header Banner (Screen 56) -->
  <div class="success-banner">
    <div class="success-check-icon">
      <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
    </div>
    <div>
      <h1 style="font-size: 24px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Pesanan berhasil dibuat!</h1>
      <p style="font-size: 14.5px; color: var(--text-muted); margin: 0;">Terima kasih telah memesan makanan favoritmu di Warmindo.</p>
    </div>
  </div>

  <!-- Order Identity Box (Screen 56) -->
  <div class="card" style="margin-bottom: 24px; padding: 32px;">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-light); padding-bottom: 24px; margin-bottom: 24px; gap: 20px;">
      <div>
        <span style="font-size: 12.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Nomor Pesanan</span>
        <div style="font-size: 32px; font-weight: 800; color: var(--primary);">#{{ $sale->sale_number }}</div>
      </div>

      <div style="display: flex; gap: 32px;">
        <div>
          <span style="font-size: 12.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Jenis Layanan</span>
          <div style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin-top: 4px; display: flex; align-items: center; gap: 6px;">
            @if($sale->order_type === 'dine_in')
              <span>🍽️ Dine In</span>
            @else
              <span>🛍️ Take Away</span>
            @endif
          </div>
        </div>

        @if($sale->order_type === 'dine_in')
          <div>
            <span style="font-size: 12.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Nomor Meja</span>
            <div style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-top: 4px;">Meja {{ $sale->table_number }}</div>
          </div>
        @endif

        <div>
          <span style="font-size: 12.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Status</span>
          <div style="margin-top: 4px;">
            <span class="badge badge-orange">
              <span class="badge-dot"></span> Menunggu Pembayaran
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Items Summary -->
    <h4 style="font-size: 15px; font-weight: 700; color: var(--text-dark); margin-bottom: 14px;">Ringkasan Menu Dipesan:</h4>
    <div style="margin-bottom: 20px;">
      @foreach($sale->items as $it)
        <div style="display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; border-bottom: 1px dashed var(--border-light);">
          <span style="color: var(--text-body);">{{ $it->product_name }} &times; {{ $it->quantity }}</span>
          <span style="font-weight: 700; color: var(--text-dark);">Rp {{ number_format($it->subtotal, 0, ',', '.') }}</span>
        </div>
      @endforeach
    </div>

    <div style="display: flex; justify-content: space-between; align-items: baseline; padding-top: 10px;">
      <span style="font-size: 16px; font-weight: 800; color: var(--text-dark);">Total Tagihan</span>
      <span style="font-size: 26px; font-weight: 800; color: var(--primary);">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
    </div>
  </div>

  <!-- Instruction Notice Box (Screen 56) -->
  <div style="background-color: #FFF9DB; border: 1.5px solid #FFE066; border-radius: var(--radius-lg); padding: 20px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px; gap: 20px;">
    <div style="display: flex; align-items: center; gap: 14px;">
      <div style="width: 40px; height: 40px; border-radius: 50%; background-color: #F59F00; color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 18px; flex-shrink: 0;">!</div>
      <p style="font-size: 14px; color: #754400; font-weight: 600; margin: 0;">
        Tunjukkan nomor pesanan <strong>#{{ $sale->sale_number }}</strong> kepada kasir untuk pembayaran. Setelah dibayar, pesananmu akan langsung dimasak oleh tim dapur!
      </p>
    </div>
    <a href="{{ route('home') }}" class="btn btn-primary" style="white-space: nowrap;">
      Kembali ke Beranda
    </a>
  </div>
</div>
@endsection
