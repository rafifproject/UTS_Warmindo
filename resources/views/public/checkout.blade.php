@extends('layouts.public')

@section('title', 'Checkout & Ringkasan Pesanan — Warmindo')

@section('content')
<div class="container" style="padding-top: 24px;">
  <div style="margin-bottom: 24px;">
    <h1 style="font-size: 26px; font-weight: 800; color: var(--text-dark); margin-bottom: 6px;">Checkout & Ringkasan Pesanan</h1>
    <p style="font-size: 13.5px; color: var(--text-muted);">Pastikan pesanan dan layanan Anda sudah benar sebelum mengirim pesanan ke kasir.</p>
  </div>

  <form action="{{ route('cart.checkout.store') }}" method="POST">
    @csrf
    <div style="display: flex; flex-wrap: wrap; gap: 28px; align-items: flex-start;">
      <!-- Left Column: Service & Items & Notes (Screen 55) -->
      <div style="flex: 1.8; min-width: 320px;">
        <!-- Informasi Layanan Card -->
        <div class="card" style="margin-bottom: 20px;">
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 14px;">
              <div style="width: 44px; height: 44px; border-radius: var(--radius-md); background-color: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                @if($service['order_type'] === 'dine_in')
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10h18M3 14h18M5 10v8M19 10v8M10 6h4M12 6v4"/></svg>
                @else
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                @endif
              </div>
              <div>
                <h4 style="font-size: 15px; font-weight: 800; color: var(--text-dark);">
                  {{ $service['order_type'] === 'dine_in' ? 'Dine In (Makan di Tempat)' : 'Take Away (Bawa Pulang)' }}
                </h4>
                <p style="font-size: 13px; color: var(--text-muted);">
                  {{ $service['order_type'] === 'dine_in' ? 'Meja ' . $service['table_number'] : 'Tanpa nomor meja' }}
                </p>
              </div>
            </div>
            <a href="{{ route('cart.service') }}" style="font-size: 13px; font-weight: 700; color: var(--primary);">
              Ubah
            </a>
          </div>
        </div>

        <!-- Daftar Pesanan Card -->
        <div class="card" style="margin-bottom: 20px;">
          <h4 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 16px;">Daftar Pesanan</h4>
          <div style="display: flex; flex-direction: column; gap: 14px;">
            @foreach($cart as $item)
              <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 14px; border-bottom: 1px solid var(--border-light);">
                <div style="display: flex; align-items: center; gap: 14px;">
                  <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="width: 48px; height: 48px; border-radius: var(--radius-md); object-fit: cover; border: 1px solid var(--border-light);">
                  <div>
                    <h5 style="font-size: 14.5px; font-weight: 700; color: var(--text-dark);">{{ $item['name'] }}</h5>
                    <p style="font-size: 12.5px; color: var(--text-muted);">Rp {{ number_format($item['price'], 0, ',', '.') }} &times; {{ $item['quantity'] }}</p>
                  </div>
                </div>
                <div style="font-weight: 700; color: var(--text-dark);">
                  Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                </div>
              </div>
            @endforeach
          </div>

          <!-- Catatan Tambahan (opsional) -->
          <div class="form-group" style="margin-top: 18px; margin-bottom: 0;">
            <label class="form-label" for="notes">Catatan Tambahan (opsional)</label>
            <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Contoh: Jangan terlalu pedas, es dipisah, kuah banyak, dll."></textarea>
          </div>
        </div>
      </div>

      <!-- Right Column: Ringkasan Pembayaran (Screen 55) -->
      <div style="flex: 1; min-width: 280px;">
        <div class="card">
          <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 18px;">Ringkasan Pembayaran</h3>

          <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: var(--text-body);">
            <span>Subtotal</span>
            <span style="font-weight: 600;">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
          </div>

          <div style="display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 14px; color: var(--text-body);">
            <span>Biaya Layanan</span>
            <span style="font-weight: 600; color: #12B886;">Rp 0</span>
          </div>

          <div style="border-top: 1.5px dashed var(--border-light); padding-top: 16px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: baseline;">
            <span style="font-size: 16px; font-weight: 700; color: var(--text-dark);">Total</span>
            <span style="font-size: 24px; font-weight: 800; color: var(--primary);">Rp {{ number_format($total, 0, ',', '.') }}</span>
          </div>

          <!-- Alert Info Kasir (Screen 55) -->
          <div style="background-color: #E7F5FF; border-radius: var(--radius-md); padding: 12px 14px; margin-bottom: 24px; display: flex; gap: 10px; align-items: flex-start;">
            <svg style="color: #1C7ED6; flex-shrink: 0; margin-top: 2px;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <p style="font-size: 12.5px; color: #1971C2; line-height: 1.5; margin: 0;">
              <strong>Info:</strong> Pembayaran dilakukan dan dikonfirmasi langsung di meja kasir setelah pesanan dibuat.
            </p>
          </div>

          <button type="submit" class="btn btn-primary btn-block btn-lg">
            Buat Pesanan
          </button>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection
