@extends('layouts.admin')

@section('title', 'Detail Pembelian #' . $purchase->purchase_number)
@section('page_title', 'Detail Transaksi Pembelian')

@section('content')
<div style="max-width: 880px; margin: 0 auto;">
  <div style="margin-bottom: 20px;">
    <a href="{{ route('admin.purchases.index') }}" style="font-size: 13.5px; font-weight: 600; color: var(--primary);">
      &larr; Kembali ke Riwayat Pembelian
    </a>
  </div>

  <div class="card" style="padding: 32px; margin-bottom: 24px;">
    <!-- Purchase Header Info (Screen 28) -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid var(--border-light); padding-bottom: 20px; margin-bottom: 24px;">
      <div>
        <span style="font-size: 13px; color: var(--text-muted); font-weight: 600;">Transaksi Pembelian Stok Masuk</span>
        <h1 style="font-size: 28px; font-weight: 800; color: var(--primary); margin: 4px 0 8px;">#{{ $purchase->purchase_number }}</h1>
        <p style="font-size: 13.5px; color: var(--text-dark); margin: 0;">
          <strong>Supplier:</strong> {{ $purchase->supplier->name ?? 'Supplier' }}
        </p>
      </div>

      <div style="text-align: right;">
        <span class="badge badge-green" style="font-size: 13px; padding: 6px 14px;">
          <span class="badge-dot"></span> Selesai (Stok Telah Masuk)
        </span>
        <div style="margin-top: 8px; font-size: 13px; color: var(--text-muted);">
          Tanggal: {{ $purchase->purchase_date->format('d F Y') }}
        </div>
        <div style="font-size: 12px; color: var(--text-muted);">
          Petugas: {{ $purchase->user->name ?? 'Admin' }}
        </div>
      </div>
    </div>

    <!-- Items Table (Screen 28) -->
    <h4 style="font-size: 15px; font-weight: 700; color: var(--text-dark); margin-bottom: 12px;">Daftar Item Masuk:</h4>
    <div class="table-responsive" style="margin-bottom: 24px;">
      <table class="table">
        <thead>
          <tr>
            <th style="width: 50px;">No.</th>
            <th>Nama Menu</th>
            <th style="text-align: center;">Jumlah Masuk</th>
            <th style="text-align: right;">Harga Beli Satuan</th>
            <th style="text-align: right;">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($purchase->items as $idx => $it)
            <tr>
              <td>{{ $idx + 1 }}</td>
              <td style="font-weight: 700; color: var(--text-dark);">
                {{ $it->product->name ?? 'Produk' }}
                <div style="font-size: 11.5px; color: var(--text-muted);">SKU: {{ $it->product->sku ?? '-' }}</div>
              </td>
              <td style="text-align: center; font-weight: 800; color: #0CA678; font-size: 15px;">
                +{{ $it->quantity }} porsi
              </td>
              <td style="text-align: right; color: var(--text-body);">{{ $it->formatted_unit_price }}</td>
              <td style="text-align: right; font-weight: 700; color: var(--text-dark);">{{ $it->formatted_subtotal }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Total calculation -->
    <div style="background-color: #F8F9FA; border-radius: var(--radius-md); padding: 18px 24px; display: flex; justify-content: space-between; align-items: baseline; border: 1px solid var(--border-light); margin-bottom: 24px;">
      <span style="font-size: 16px; font-weight: 700; color: var(--text-dark);">Total Nilai Pembelian:</span>
      <span style="font-size: 26px; font-weight: 800; color: var(--primary);">{{ $purchase->formatted_total }}</span>
    </div>

    @if($purchase->notes)
      <div style="background-color: #FFF9F2; border-left: 3px solid var(--primary); padding: 12px 16px; border-radius: var(--radius-sm); font-size: 13px; color: var(--text-dark); margin-bottom: 24px;">
        <strong>Catatan:</strong> {{ $purchase->notes }}
      </div>
    @endif

    <div style="display: flex; justify-content: space-between;">
      <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary">
        &larr; Kembali
      </a>
      <a href="{{ route('admin.products.stock-mutations') }}" class="btn btn-primary">
        Lihat Mutasi Stok &rarr;
      </a>
    </div>
  </div>
</div>
@endsection
