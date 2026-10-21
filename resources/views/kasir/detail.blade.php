@extends('layouts.kasir')

@section('title', 'Detail Pesanan #' . $sale->sale_number . ' — Kasir Warmindo')

@section('content')
<div class="card" style="max-width: 860px; margin: 0 auto; padding: 32px;">
  <!-- Header Info (Screen 45) -->
  <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid var(--border-light); padding-bottom: 20px; margin-bottom: 24px;">
    <div>
      <span style="font-size: 13px; color: var(--text-muted); font-weight: 600;">Detail Pesanan</span>
      <h1 style="font-size: 28px; font-weight: 800; color: var(--primary); margin: 4px 0 8px;">#{{ $sale->sale_number }}</h1>
      <p style="font-size: 13px; color: var(--text-muted); margin: 0;">{{ $sale->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
    </div>

    <div style="text-align: right;">
      @if($sale->payment_status === 'pending')
        <span class="badge badge-orange" style="font-size: 13px; padding: 6px 14px;">
          <span class="badge-dot"></span> Menunggu Pembayaran
        </span>
      @elseif($sale->payment_status === 'paid')
        <span class="badge badge-green" style="font-size: 13px; padding: 6px 14px;">
          <span class="badge-dot"></span> Lunas
        </span>
      @else
        <span class="badge badge-red" style="font-size: 13px; padding: 6px 14px;">
          Dibatalkan
        </span>
      @endif
      <div style="margin-top: 10px; font-size: 13.5px; font-weight: 700; color: var(--text-dark);">
        {{ $sale->order_type === 'dine_in' ? 'Meja ' . $sale->table_number . ' (Dine In)' : 'Take Away (Bungkus)' }}
      </div>
      <div style="font-size: 12px; color: var(--text-muted);">
        Kasir: {{ auth()->user()->name ?? 'Staff' }}
      </div>
    </div>
  </div>

  @if($sale->notes)
    <div style="background-color: #FFF9F2; border-left: 3px solid var(--primary); padding: 10px 14px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 13px; color: var(--text-dark);">
      <strong>Catatan Pelanggan:</strong> {{ $sale->notes }}
    </div>
  @endif

  <!-- Itemized List Table (Screen 45) -->
  <h4 style="font-size: 15px; font-weight: 700; color: var(--text-dark); margin-bottom: 12px;">Daftar Pesanan:</h4>
  <div class="table-responsive" style="margin-bottom: 24px;">
    <table class="table">
      <thead>
        <tr>
          <th style="width: 50px;">No.</th>
          <th>Menu</th>
          <th style="text-align: center;">Qty</th>
          <th style="text-align: right;">Harga Satuan</th>
          <th style="text-align: right;">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($sale->items as $idx => $it)
          <tr>
            <td>{{ $idx + 1 }}</td>
            <td>
              <span style="font-weight: 700; color: var(--text-dark);">{{ $it->product_name }}</span>
              @if($it->notes)
                <div style="font-size: 11.5px; color: var(--text-muted);">Note: {{ $it->notes }}</div>
              @endif
            </td>
            <td style="text-align: center; font-weight: 700;">{{ $it->quantity }}</td>
            <td style="text-align: right; color: var(--text-body);">Rp {{ number_format($it->price, 0, ',', '.') }}</td>
            <td style="text-align: right; font-weight: 700; color: var(--text-dark);">Rp {{ number_format($it->subtotal, 0, ',', '.') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Tagihan Calculations Box (Screen 45) -->
  <div style="background-color: #F8F9FA; border-radius: var(--radius-md); padding: 20px; border: 1px solid var(--border-light); margin-bottom: 28px;">
    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14.5px; color: var(--text-body);">
      <span>Total Tagihan:</span>
      <span style="font-weight: 800; font-size: 16px; color: var(--text-dark);">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
    </div>
    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14.5px; color: var(--text-body);">
      <span>Pembayaran:</span>
      <span style="font-weight: 600;">{{ $sale->payment_status === 'paid' ? 'Rp ' . number_format($sale->cash_received, 0, ',', '.') : 'Rp 0' }}</span>
    </div>
    <div style="border-top: 1px dashed var(--border-default); padding-top: 12px; display: flex; justify-content: space-between; font-size: 16px; font-weight: 800;">
      <span>Sisa Tagihan:</span>
      <span style="color: {{ $sale->payment_status === 'paid' ? '#12B886' : 'var(--primary)' }};">
        {{ $sale->payment_status === 'paid' ? 'LUNAS' : 'Rp ' . number_format($sale->total_amount, 0, ',', '.') }}
      </span>
    </div>
  </div>

  <div style="display: flex; justify-content: space-between; align-items: center;">
    <a href="{{ route('kasir.waiting') }}" class="btn btn-secondary">
      &larr; Kembali
    </a>

    @if($sale->payment_status === 'pending')
      <a href="{{ route('kasir.pay', $sale->sale_number) }}" class="btn btn-primary btn-lg">
        Proses Pembayaran &rarr;
      </a>
    @else
      <a href="{{ route('kasir.receipt', $sale->sale_number) }}" class="btn btn-primary btn-lg">
        Cetak Struk / Bukti Pembayaran
      </a>
    @endif
  </div>
</div>
@endsection
