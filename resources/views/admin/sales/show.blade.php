@extends('layouts.admin')

@section('title', 'Detail Penjualan #' . $sale->sale_number)
@section('page_title', 'Detail Transaksi Penjualan')

@section('content')
<div style="max-width: 880px; margin: 0 auto;">
  <div style="margin-bottom: 20px;">
    <a href="{{ route('admin.sales.index') }}" style="font-size: 13.5px; font-weight: 600; color: var(--primary);">
      &larr; Kembali ke Daftar Penjualan
    </a>
  </div>

  <div class="card" style="padding: 32px; margin-bottom: 24px;">
    <!-- Sale Header (Screen 31) -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid var(--border-light); padding-bottom: 20px; margin-bottom: 24px;">
      <div>
        <span style="font-size: 13px; color: var(--text-muted); font-weight: 600;">Transaksi Penjualan</span>
        <h1 style="font-size: 28px; font-weight: 800; color: var(--primary); margin: 4px 0 8px;">#{{ $sale->sale_number }}</h1>
        <div style="font-size: 13.5px; color: var(--text-dark);">
          <strong>Layanan:</strong> {{ $sale->order_type === 'dine_in' ? 'Dine In (Meja ' . $sale->table_number . ')' : 'Take Away (Bungkus)' }}
        </div>
      </div>

      <div style="text-align: right;">
        @if($sale->payment_status === 'paid')
          <span class="badge badge-green" style="font-size: 13px; padding: 6px 14px;"><span class="badge-dot"></span> Lunas</span>
        @elseif($sale->payment_status === 'pending')
          <span class="badge badge-orange" style="font-size: 13px; padding: 6px 14px;"><span class="badge-dot"></span> Menunggu</span>
        @else
          <span class="badge badge-red" style="font-size: 13px; padding: 6px 14px;">Dibatalkan</span>
        @endif
        <div style="margin-top: 8px; font-size: 12.5px; color: var(--text-muted);">
          {{ $sale->created_at->format('d F Y, H:i') }} WIB
        </div>
        @if($sale->user)
          <div style="font-size: 12px; color: var(--text-muted);">
            Diproses oleh: {{ $sale->user->name }}
          </div>
        @endif
      </div>
    </div>

    <!-- Items Table (Screen 31) -->
    <h4 style="font-size: 15px; font-weight: 700; color: var(--text-dark); margin-bottom: 12px;">Rincian Menu:</h4>
    <div class="table-responsive" style="margin-bottom: 24px;">
      <table class="table">
        <thead>
          <tr>
            <th style="width: 50px;">No.</th>
            <th>Nama Menu</th>
            <th style="text-align: center;">Qty</th>
            <th style="text-align: right;">Harga Satuan</th>
            <th style="text-align: right;">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($sale->items as $idx => $it)
            <tr>
              <td>{{ $idx + 1 }}</td>
              <td style="font-weight: 700; color: var(--text-dark);">
                {{ $it->product_name }}
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

    <!-- Payment info & total -->
    <div style="background-color: #F8F9FA; border-radius: var(--radius-md); padding: 20px; border: 1px solid var(--border-light); margin-bottom: 24px;">
      <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14.5px;">
        <span>Subtotal:</span>
        <span style="font-weight: 600;">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
      </div>
      <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14.5px;">
        <span>Biaya Layanan:</span>
        <span style="font-weight: 600; color: #12B886;">Rp 0</span>
      </div>
      <div style="border-top: 1px dashed var(--border-default); padding-top: 10px; display: flex; justify-content: space-between; font-size: 18px; font-weight: 800;">
        <span>Total Tagihan:</span>
        <span style="color: var(--primary);">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
      </div>
      @if($sale->payment_status === 'paid')
        <div style="margin-top: 12px; padding-top: 10px; border-top: 1px solid var(--border-light); font-size: 13px; color: var(--text-muted); display: flex; justify-content: space-between;">
          <span>Metode: <strong style="color: var(--text-dark); text-transform: uppercase;">{{ $sale->payment_method ?? 'TUNAI' }}</strong></span>
          <span>Uang Diterima: <strong>Rp {{ number_format($sale->cash_received, 0, ',', '.') }}</strong> | Kembalian: <strong>Rp {{ number_format($sale->cash_change, 0, ',', '.') }}</strong></span>
        </div>
      @endif
    </div>

    @if($sale->notes)
      <div style="background-color: #FFF9F2; border-left: 3px solid var(--primary); padding: 12px 16px; border-radius: var(--radius-sm); font-size: 13px; color: var(--text-dark); margin-bottom: 24px;">
        <strong>Catatan:</strong> {{ $sale->notes }}
      </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center;">
      <a href="{{ route('admin.sales.index') }}" class="btn btn-secondary">
        &larr; Kembali
      </a>

      <div style="display: flex; gap: 10px;">
        @if($sale->payment_status !== 'cancelled')
          <!-- Cancel Transaction with atomic rollback -->
          <form action="{{ route('admin.sales.cancel', $sale->id) }}" method="POST" onsubmit="return confirm('PENTING: Membatalkan transaksi ini akan mengembalikan stok produk yang telah terjual ke inventori gudang. Lanjutkan pembatalan?');">
            @csrf
            <button type="submit" class="btn btn-danger">
              Batalkan Transaksi &amp; Pulihkan Stok
            </button>
          </form>
        @endif

        @if($sale->payment_status === 'paid')
          <a href="{{ route('kasir.receipt', $sale->sale_number) }}" target="_blank" class="btn btn-primary">
            Cetak Struk Transaksi
          </a>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
