@extends('layouts.admin')

@section('title', 'Detail Produk — ' . $product->name)
@section('page_title', 'Detail Menu & Riwayat Stok')

@section('content')
<div style="max-width: 920px; margin: 0 auto;">
  <div style="margin-bottom: 20px;">
    <a href="{{ route('admin.products.index') }}" style="font-size: 13.5px; font-weight: 600; color: var(--primary);">
      &larr; Kembali ke Daftar Produk
    </a>
  </div>

  <div class="card" style="padding: 32px; margin-bottom: 24px;">
    <div style="display: flex; flex-wrap: wrap; gap: 32px; align-items: flex-start;">
      <!-- Product Photo -->
      <div style="width: 200px; height: 200px; border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--border-light); flex-shrink: 0;">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
      </div>

      <!-- Info Details (Screen 17) -->
      <div style="flex: 1; min-width: 280px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
          <div>
            <span class="badge badge-blue">{{ $product->category->name ?? 'Menu' }}</span>
            <span style="font-size: 12.5px; color: var(--text-muted); margin-left: 8px;">SKU: {{ $product->sku }}</span>
          </div>
          <div>
            @if($product->is_available)
              <span class="badge badge-green"><span class="badge-dot"></span> Tersedia</span>
            @else
              <span class="badge badge-red"><span class="badge-dot"></span> Tidak Tersedia</span>
            @endif
          </div>
        </div>

        <h1 style="font-size: 26px; font-weight: 800; color: var(--text-dark); margin-bottom: 8px;">{{ $product->name }}</h1>
        <p style="font-size: 14px; color: var(--text-body); line-height: 1.5; margin-bottom: 20px;">
          {{ $product->description ?? 'Tidak ada deskripsi.' }}
        </p>

        <!-- Metric Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 14px; background-color: #F8F9FA; padding: 16px; border-radius: var(--radius-md); border: 1px solid var(--border-light);">
          <div>
            <span style="font-size: 12px; color: var(--text-muted);">Harga Beli</span>
            <div style="font-weight: 700; color: var(--text-dark); font-size: 15px;">{{ $product->formatted_buy_price }}</div>
          </div>
          <div>
            <span style="font-size: 12px; color: var(--text-muted);">Harga Jual</span>
            <div style="font-weight: 800; color: var(--primary); font-size: 16px;">{{ $product->formatted_sell_price }}</div>
          </div>
          <div>
            <span style="font-size: 12px; color: var(--text-muted);">Stok Saat Ini</span>
            <div style="font-weight: 800; color: {{ $product->stock <= $product->min_stock ? '#FA5252' : '#0CA678' }}; font-size: 16px;">
              {{ $product->stock }} porsi
            </div>
          </div>
          <div>
            <span style="font-size: 12px; color: var(--text-muted);">Batas Minimum</span>
            <div style="font-weight: 700; color: var(--text-dark); font-size: 15px;">{{ $product->min_stock }} porsi</div>
          </div>
        </div>

        <div style="margin-top: 20px; display: flex; gap: 10px;">
          <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary btn-sm">Edit Produk</a>
          <a href="{{ route('menu.detail', $product->slug) }}" target="_blank" class="btn btn-secondary btn-sm">Lihat di Katalog</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Riwayat Pergerakan Stok Tab (Screen 17 & 20) -->
  <div class="card" style="padding: 0; overflow: hidden;">
    <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-light);">
      <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark);">Riwayat Pergerakan Stok (Audit Mutasi)</h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Log setiap stok masuk dari pembelian dan stok keluar dari penjualan kasir</p>
    </div>

    <div class="table-responsive">
      <table class="table" style="margin: 0;">
        <thead>
          <tr>
            <th style="padding-left: 24px;">Waktu</th>
            <th>Tipe</th>
            <th>No. Referensi</th>
            <th style="text-align: center;">Perubahan</th>
            <th style="text-align: center;">Sisa Stok</th>
            <th>Petugas / Alasan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($product->stockMutations as $mut)
            <tr>
              <td style="padding-left: 24px; font-size: 12.5px; color: var(--text-muted);">
                {{ $mut->created_at->format('d/m/Y H:i') }}
              </td>
              <td>
                @if($mut->type === 'in')
                  <span class="badge badge-green">Masuk</span>
                @elseif($mut->type === 'out')
                  <span class="badge badge-orange">Keluar</span>
                @else
                  <span class="badge badge-blue">Penyesuaian</span>
                @endif
              </td>
              <td style="font-weight: 600;">{{ $mut->reference_number ?? '-' }}</td>
              <td style="text-align: center; font-weight: 800; color: {{ $mut->type === 'in' ? '#0CA678' : '#FA5252' }};">
                {{ $mut->type === 'in' ? '+' : '-' }}{{ $mut->quantity }}
              </td>
              <td style="text-align: center; font-weight: 700;">
                {{ $mut->after_stock }}
              </td>
              <td style="font-size: 13px; color: var(--text-body);">
                {{ $mut->notes }} <span style="color: var(--text-muted);">({{ $mut->user->name ?? 'Sistem' }})</span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align: center; padding: 24px; color: var(--text-muted);">
                Belum ada catatan mutasi stok untuk produk ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
