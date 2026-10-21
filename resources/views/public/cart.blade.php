@extends('layouts.public')

@section('title', 'Keranjang Belanja — Warmindo')

@section('content')
<div class="container" style="padding-top: 24px;">
  <div style="margin-bottom: 24px;">
    <h1 style="font-size: 26px; font-weight: 800; color: var(--text-dark);">Keranjang Belanja</h1>
    <p style="font-size: 13.5px; color: var(--text-muted);">Periksa menu pilihan Anda sebelum melanjutkan ke pemilihan layanan meja.</p>
  </div>

  @if(!empty($cart) && count($cart) > 0)
    <div style="display: flex; flex-wrap: wrap; gap: 28px; align-items: flex-start;">
      <!-- Cart Items Table (Screen 53) -->
      <div style="flex: 2; min-width: 320px;">
        <div class="card" style="padding: 0; overflow: hidden;">
          <div class="table-responsive">
            <table class="table" style="margin: 0;">
              <thead>
                <tr>
                  <th style="padding-left: 24px;">Item</th>
                  <th>Harga Satuan</th>
                  <th style="text-align: center;">Jumlah</th>
                  <th>Subtotal</th>
                  <th style="text-align: center; padding-right: 24px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($cart as $id => $item)
                  <tr>
                    <td style="padding-left: 24px;">
                      <div style="display: flex; align-items: center; gap: 14px;">
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="width: 54px; height: 54px; border-radius: var(--radius-md); object-fit: cover; border: 1px solid var(--border-light);">
                        <div>
                          <div style="font-weight: 700; color: var(--text-dark); font-size: 14.5px;">{{ $item['name'] }}</div>
                          <div style="font-size: 12px; color: var(--text-muted);">SKU: {{ $item['sku'] ?? 'WMW-00' }}</div>
                        </div>
                      </div>
                    </td>
                    <td style="font-weight: 600; color: var(--text-body);">
                      Rp {{ number_format($item['price'], 0, ',', '.') }}
                    </td>
                    <td style="text-align: center;">
                      <div style="display: inline-flex; align-items: center; border: 1.5px solid var(--border-default); border-radius: var(--radius-md); overflow: hidden; background: #FFFFFF;">
                        <form action="{{ route('cart.update', $id) }}" method="POST" style="margin: 0;">
                          @csrf
                          <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                          <button type="submit" style="width: 32px; height: 32px; background: none; border: none; font-size: 16px; font-weight: 700; cursor: pointer; color: var(--text-body);">-</button>
                        </form>
                        <span style="width: 36px; text-align: center; font-size: 14px; font-weight: 700; color: var(--text-dark);">{{ $item['quantity'] }}</span>
                        <form action="{{ route('cart.update', $id) }}" method="POST" style="margin: 0;">
                          @csrf
                          <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                          <button type="submit" style="width: 32px; height: 32px; background: none; border: none; font-size: 16px; font-weight: 700; cursor: pointer; color: var(--text-body);">+</button>
                        </form>
                      </div>
                    </td>
                    <td style="font-weight: 700; color: var(--text-dark);">
                      Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                    </td>
                    <td style="text-align: center; padding-right: 24px;">
                      <form action="{{ route('cart.remove', $id) }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" title="Hapus menu" style="background: none; border: none; color: #FA5252; cursor: pointer; padding: 6px;">
                          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line>
                          </svg>
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <div style="margin-top: 16px;">
          <a href="{{ route('menu') }}" class="btn btn-secondary">
            &larr; Lanjut Belanja
          </a>
        </div>
      </div>

      <!-- Ringkasan Pesanan Card (Screen 53) -->
      <div style="flex: 1; min-width: 280px;">
        <div class="card">
          <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 18px;">Ringkasan Pesanan</h3>
          
          <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: var(--text-body);">
            <span>Subtotal</span>
            <span style="font-weight: 600;">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
          </div>

          <div style="display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 14px; color: var(--text-body);">
            <span>Biaya Layanan</span>
            <span style="font-weight: 600; color: #12B886;">Rp 0</span>
          </div>

          <div style="border-top: 1.5px dashed var(--border-light); padding-top: 16px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: baseline;">
            <span style="font-size: 16px; font-weight: 700; color: var(--text-dark);">Total</span>
            <span style="font-size: 24px; font-weight: 800; color: var(--primary);">Rp {{ number_format($total, 0, ',', '.') }}</span>
          </div>

          <a href="{{ route('cart.service') }}" class="btn btn-primary btn-block btn-lg">
            Lanjut Pilih Layanan &rarr;
          </a>
        </div>
      </div>
    </div>
  @else
    <!-- Data Kosong State (Screen 61) -->
    <div class="card state-box" style="margin-top: 20px;">
      <img src="{{ asset('images/empty-box.svg') }}" alt="Keranjang Kosong" class="state-img">
      <h3 class="state-title">Keranjang Anda masih kosong</h3>
      <p class="state-desc">Belum ada menu yang Anda masukkan ke dalam keranjang belanja.</p>
      <a href="{{ route('menu') }}" class="btn btn-primary btn-lg">
        + Pilih Menu Sekarang
      </a>
    </div>
  @endif
</div>
@endsection
