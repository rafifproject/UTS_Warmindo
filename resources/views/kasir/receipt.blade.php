@extends('layouts.kasir')

@section('title', 'Bukti Pembayaran / Struk #' . $sale->sale_number)

@section('content')
<div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 32px; align-items: flex-start; padding-top: 10px;">
  <!-- Receipt Paper View (Screen 49) -->
  <div class="receipt-paper">
    <div style="text-align: center; margin-bottom: 12px;">
      <img src="{{ asset('images/logo.svg') }}" alt="Warmindo Logo" style="height: 38px; margin-bottom: 4px;">
      <h3 style="font-size: 15px; font-weight: 800; letter-spacing: 0.5px; margin: 0;">BUKTI PEMBAYARAN</h3>
      <p style="font-size: 11px; color: #495057; margin: 2px 0 0;">Makan Enak, Harga Bersahabat</p>
    </div>

    <div class="receipt-dashed"></div>

    <div style="font-size: 12px; line-height: 1.5;">
      <div style="display: flex; justify-content: space-between;">
        <span>No. Pesanan</span>
        <span style="font-weight: 700;">#{{ $sale->sale_number }}</span>
      </div>
      <div style="display: flex; justify-content: space-between;">
        <span>Layanan</span>
        <span>{{ $sale->order_type === 'dine_in' ? 'Dine In (Meja ' . $sale->table_number . ')' : 'Take Away' }}</span>
      </div>
      <div style="display: flex; justify-content: space-between;">
        <span>Kasir</span>
        <span>{{ $sale->user->name ?? 'Kasir Warmindo' }}</span>
      </div>
      <div style="display: flex; justify-content: space-between;">
        <span>Waktu</span>
        <span>{{ $sale->paid_at ? $sale->paid_at->format('d/m/Y H:i') : $sale->created_at->format('d/m/Y H:i') }}</span>
      </div>
    </div>

    <div class="receipt-dashed"></div>

    <!-- Items Table -->
    <table style="width: 100%; font-size: 12px; border-collapse: collapse;">
      <thead>
        <tr style="text-align: left; border-bottom: 1px dashed #495057;">
          <th style="padding: 4px 0;">Menu</th>
          <th style="text-align: center; padding: 4px 0;">Qty</th>
          <th style="text-align: right; padding: 4px 0;">Harga</th>
          <th style="text-align: right; padding: 4px 0;">Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach($sale->items as $it)
          <tr>
            <td style="padding: 4px 0;">{{ $it->product_name }}</td>
            <td style="text-align: center; padding: 4px 0;">{{ $it->quantity }}</td>
            <td style="text-align: right; padding: 4px 0;">{{ number_format($it->price, 0, ',', '.') }}</td>
            <td style="text-align: right; padding: 4px 0;">{{ number_format($it->subtotal, 0, ',', '.') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="receipt-dashed"></div>

    <div style="font-size: 12.5px; line-height: 1.6;">
      <div style="display: flex; justify-content: space-between; font-weight: 700;">
        <span>Total:</span>
        <span>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
      </div>
      <div style="display: flex; justify-content: space-between;">
        <span>Uang Diterima:</span>
        <span>Rp {{ number_format($sale->cash_received ?? $sale->total_amount, 0, ',', '.') }}</span>
      </div>
      <div style="display: flex; justify-content: space-between; font-weight: 700;">
        <span>Kembalian:</span>
        <span>Rp {{ number_format($sale->cash_change ?? 0, 0, ',', '.') }}</span>
      </div>
      <div style="display: flex; justify-content: space-between; font-size: 11px; color: #495057; margin-top: 4px;">
        <span>Metode:</span>
        <span style="text-transform: uppercase;">{{ $sale->payment_method ?? 'TUNAI' }}</span>
      </div>
    </div>

    <div class="receipt-dashed"></div>

    <div style="text-align: center; font-size: 11.5px; margin-top: 10px;">
      <p style="margin: 0; font-weight: 700;">Terima kasih telah berkunjung!</p>
      <p style="margin: 2px 0 0;">Selamat menikmati sajian kami 🍜</p>
    </div>
  </div>

  <!-- Side Actions (Screen 49) -->
  <div class="card no-print" style="width: 280px; padding: 24px;">
    <h4 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 16px;">Aksi Struk</h4>

    <button onclick="window.print()" class="btn btn-primary btn-block" style="margin-bottom: 10px;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
      Cetak Struk
    </button>

    <a href="{{ route('kitchen.dashboard') }}" class="btn btn-outline-primary btn-block" style="margin-bottom: 10px;" target="_blank">
      Lihat Antrean Dapur
    </a>

    <a href="{{ route('kasir.dashboard') }}" class="btn btn-secondary btn-block">
      Kembali ke Kasir
    </a>
  </div>
</div>

<!-- Modal Konfirmasi Pembayaran & Kembalian (Screen 48) -->
@if(session('payment_success_modal'))
<div id="modalPaymentSuccess" class="modal-backdrop active">
  <div class="modal-dialog">
    <div class="modal-icon-circle success">
      <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
    </div>

    <h3 style="font-size: 20px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Pembayaran Berhasil!</h3>
    <p style="font-size: 13.5px; color: var(--primary); font-weight: 700; margin-bottom: 18px;">Nomor Pesanan #{{ $sale->sale_number }}</p>

    <div style="background-color: #F8F9FA; border-radius: var(--radius-md); padding: 16px; margin-bottom: 24px; text-align: left; font-size: 13.5px;">
      <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
        <span>Total Tagihan:</span>
        <span style="font-weight: 700;">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
      </div>
      <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
        <span>Uang Diterima:</span>
        <span style="font-weight: 700;">Rp {{ number_format($sale->cash_received, 0, ',', '.') }}</span>
      </div>
      <div style="border-top: 1px dashed var(--border-default); padding-top: 8px; display: flex; justify-content: space-between; font-size: 15px;">
        <span style="font-weight: 800; color: var(--text-dark);">Kembalian:</span>
        <span style="font-weight: 800; color: #0CA678;">Rp {{ number_format($sale->cash_change, 0, ',', '.') }}</span>
      </div>
      <div style="border-top: 1px solid var(--border-light); margin-top: 12px; padding-top: 8px; font-size: 12px; color: var(--text-muted); display: flex; justify-content: space-between;">
        <span>Kasir: {{ $sale->user->name ?? 'Kasir' }}</span>
        <span>{{ $sale->paid_at ? $sale->paid_at->format('d M Y H:i') : now()->format('d M Y H:i') }}</span>
      </div>
    </div>

    <div style="display: flex; gap: 10px;">
      <button type="button" class="btn btn-secondary" style="flex: 1;" data-close-modal onclick="window.print()">
        Cetak Struk
      </button>
      <button type="button" class="btn btn-primary" style="flex: 1;" data-close-modal>
        Selesai
      </button>
    </div>
  </div>
</div>
@endif
@endsection
