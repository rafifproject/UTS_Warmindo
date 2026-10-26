@extends('layouts.public')

@section('title', 'Pilihan Layanan — Warmindo')

@section('content')
<div class="container" style="padding-top: 24px; max-width: 800px;">
  <div style="margin-bottom: 28px;">
    <h1 style="font-size: 26px; font-weight: 800; color: var(--text-dark); margin-bottom: 6px;">Pilih Layanan</h1>
    <p style="font-size: 14px; color: var(--text-muted);">Tentukan apakah Anda ingin makan di tempat (Dine In) atau bawa pulang (Take Away).</p>
  </div>

  @if($errors->any())
    <div style="background-color: #FFE3E3; border-left: 4px solid #FA5252; color: #E03131; padding: 12px 18px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 24px;">
      {{ $errors->first() }}
    </div>
  @endif

  <form action="{{ route('cart.service.save') }}" method="POST">
    @csrf
    <input type="hidden" name="order_type" id="order_type" value="{{ $serviceData['order_type'] ?? 'dine_in' }}">

    <!-- Service Selection Cards (Screen 54) -->
    <div class="service-cards">
      <!-- Dine In Option -->
      <div class="service-card {{ ($serviceData['order_type'] ?? 'dine_in') === 'dine_in' ? 'selected' : '' }}" data-service="dine_in">
        <div class="service-icon-box">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 10h18M3 14h18M5 10v8M19 10v8M10 6h4M12 6v4"/>
          </svg>
        </div>
        <div>
          <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Dine In</h3>
          <p style="font-size: 13px; color: var(--text-muted);">Makan di tempat dengan nomor meja.</p>
        </div>
        <div class="service-radio-circle"></div>
      </div>

      <!-- Take Away Option -->
      <div class="service-card {{ ($serviceData['order_type'] ?? '') === 'take_away' ? 'selected' : '' }}" data-service="take_away">
        <div class="service-icon-box">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
          </svg>
        </div>
        <div>
          <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Take Away</h3>
          <p style="font-size: 13px; color: var(--text-muted);">Bawa pulang tanpa nomor meja.</p>
        </div>
        <div class="service-radio-circle"></div>
      </div>
    </div>

    <!-- Conditional Table Number Input (Screen 54) -->
    <div id="tableNumberGroup" class="card" style="margin-bottom: 28px; {{ ($serviceData['order_type'] ?? 'dine_in') === 'take_away' ? 'display: none;' : '' }}">
      <div class="form-group" style="margin-bottom: 0;">
        <label class="form-label" for="table_number">
          Nomor Meja <span class="required">*</span>
        </label>
        <input type="text" name="table_number" id="table_number" value="{{ $serviceData['table_number'] ?? '03' }}" class="form-control {{ $errors->has('table_number') ? 'is-invalid' : '' }}" placeholder="contoh: 03" style="font-size: 16px; font-weight: 700; max-width: 280px;">
        <p style="font-size: 12.5px; color: var(--text-muted); margin-top: 6px;">
          Wajib diisi untuk pesanan makan di tempat (Dine In).
        </p>
      </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center;">
      <a href="{{ route('cart.index') }}" class="btn btn-secondary">
        &larr; Kembali ke Keranjang
      </a>
      <button type="submit" class="btn btn-primary btn-lg">
        Lanjut Checkout &rarr;
      </button>
    </div>
  </form>
</div>
@endsection
