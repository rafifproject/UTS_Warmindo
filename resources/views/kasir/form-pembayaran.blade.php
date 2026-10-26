@extends('layouts.kasir')

@section('title', 'Form Pembayaran Tunai #' . $sale->sale_number . ' — Kasir Warmindo')

@section('content')
<div class="card" style="max-width: 640px; margin: 0 auto; padding: 36px;">
  <!-- Header (Screen 46) -->
  <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border-light); padding-bottom: 16px;">
    <h2 style="font-size: 20px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Form Pembayaran Tunai</h2>
    <div style="display: flex; gap: 12px; font-size: 13.5px; color: var(--text-muted);">
      <span style="font-weight: 800; color: var(--primary);">#{{ $sale->sale_number }}</span>
      <span>&bull;</span>
      <span>{{ $sale->order_type === 'dine_in' ? 'Meja ' . $sale->table_number : 'Take Away' }}</span>
      <span>&bull;</span>
      <span>{{ $sale->created_at->format('d M Y H:i') }}</span>
    </div>
  </div>

  <form id="kasirPaymentForm" action="{{ route('kasir.pay.post', $sale->sale_number) }}" method="POST">
    @csrf
    <input type="hidden" name="payment_method" value="cash">

    <!-- Total Tagihan -->
    <div style="background-color: #F8F9FA; border-radius: var(--radius-md); padding: 18px 24px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: baseline; border: 1px solid var(--border-light);">
      <span style="font-size: 15px; font-weight: 700; color: var(--text-dark);">Total Tagihan:</span>
      <span id="total_bill_amount" data-total="{{ $sale->total_amount }}" style="font-size: 26px; font-weight: 800; color: var(--primary);">
        Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
      </span>
    </div>

    <!-- Uang Diterima Input with Quick Shortcut Buttons (Screen 46) -->
    <div class="form-group" style="margin-bottom: 24px;">
      <label class="form-label" for="cash_received" style="font-size: 14.5px;">
        Uang Diterima <span class="required">*</span>
      </label>
      <input type="number" name="cash_received" id="cash_received" class="form-control" placeholder="Masukkan nominal uang tunai..." value="{{ old('cash_received', 50000) }}" style="font-size: 18px; font-weight: 700; padding: 12px 16px;" required autofocus>
      
      <!-- Quick Cash Buttons (Screen 46: 50.000, 100.000, 200.000, pas) -->
      <div class="cash-shortcuts">
        <button type="button" class="btn-cash-shortcut" data-amount="{{ (int)$sale->total_amount }}">Uang Pas</button>
        <button type="button" class="btn-cash-shortcut" data-amount="50000">50.000</button>
        <button type="button" class="btn-cash-shortcut" data-amount="100000">100.000</button>
        <button type="button" class="btn-cash-shortcut" data-amount="200000">200.000</button>
      </div>
    </div>

    <!-- Kembalian Display (Screen 46) -->
    <div style="background-color: #F4FBF7; border: 1.5px solid #C3FAE8; border-radius: var(--radius-md); padding: 18px 24px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: baseline;">
      <span style="font-size: 15px; font-weight: 700; color: var(--text-dark);">Kembalian:</span>
      <span id="cash_change_display" style="font-size: 24px; font-weight: 800; color: #0CA678;">
        Rp 10.000
      </span>
    </div>

    <!-- Warning helper box -->
    <div style="background-color: #FFF9DB; border-radius: var(--radius-md); padding: 12px 16px; margin-bottom: 28px; display: flex; gap: 10px; align-items: center; border: 1px solid #FFE066;">
      <span style="font-size: 18px;">💡</span>
      <p style="font-size: 12.5px; color: #754400; margin: 0; line-height: 1.4;">
        Pastikan nominal uang sesuai dengan total tagihan. Sistem akan menghitung kembalian secara otomatis dan meneruskan pesanan ke antrean dapur.
      </p>
    </div>

    <!-- Submit buttons -->
    <div style="display: flex; justify-content: space-between; gap: 14px;">
      <a href="{{ route('kasir.waiting') }}" class="btn btn-secondary" style="flex: 1;">
        Batal
      </a>
      <button type="submit" class="btn btn-primary btn-lg" style="flex: 2;">
        Konfirmasi Pembayaran
      </button>
    </div>
  </form>
</div>

<!-- Modal Validasi Uang Kurang (Screen 47) -->
<div id="modalUangKurang" class="modal-backdrop {{ session('show_insufficient_cash_modal') ? 'active' : '' }}">
  <div class="modal-dialog">
    <div class="modal-icon-circle warning">
      <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
    </div>
    
    <h3 style="font-size: 20px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px;">Uang Kurang!</h3>
    <p style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 20px;">
      Jumlah uang yang Anda masukkan kurang dari total tagihan pesanan.
    </p>

    <div style="background-color: #F8F9FA; border-radius: var(--radius-md); padding: 16px; margin-bottom: 24px; text-align: left; font-size: 13.5px;">
      <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
        <span>Total Tagihan:</span>
        <span style="font-weight: 700;">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
      </div>
      <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
        <span>Uang Diterima:</span>
        <span style="font-weight: 700;">Rp {{ number_format(old('cash_received', 0), 0, ',', '.') }}</span>
      </div>
      <div style="border-top: 1px dashed var(--border-default); padding-top: 8px; display: flex; justify-content: space-between; color: #FA5252; font-weight: 800;">
        <span>Kekurangan:</span>
        <span id="kekuranganAmount">Rp {{ number_format(session('shortage', 0), 0, ',', '.') }}</span>
      </div>
    </div>

    <div style="display: flex; gap: 10px;">
      <button type="button" class="btn btn-secondary" style="flex: 1;" data-close-modal>
        Kembali
      </button>
      <button type="button" class="btn btn-primary" style="flex: 1;" data-close-modal onclick="document.getElementById('cash_received').focus(); document.getElementById('cash_received').select();">
        Ulangi Input
      </button>
    </div>
  </div>
</div>
@endsection
