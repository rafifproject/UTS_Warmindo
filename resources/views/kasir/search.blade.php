@extends('layouts.kasir')

@section('title', 'Pencarian Pesanan — Kasir Warmindo')

@section('content')
<div class="card" style="margin-bottom: 24px; padding: 24px;">
  <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 16px;">Cari Pesanan</h2>

  <!-- Search Filter Tabs (Screen 44: Semua, Dine In, Take Away) -->
  <div style="display: flex; gap: 8px; margin-bottom: 18px;">
    <a href="{{ route('kasir.search', ['tab' => 'all', 'q' => request('q')]) }}" class="btn btn-sm {{ $tab === 'all' ? 'btn-primary' : 'btn-secondary' }}">
      Semua
    </a>
    <a href="{{ route('kasir.search', ['tab' => 'dine_in', 'q' => request('q')]) }}" class="btn btn-sm {{ $tab === 'dine_in' ? 'btn-primary' : 'btn-secondary' }}">
      Dine In
    </a>
    <a href="{{ route('kasir.search', ['tab' => 'take_away', 'q' => request('q')]) }}" class="btn btn-sm {{ $tab === 'take_away' ? 'btn-primary' : 'btn-secondary' }}">
      Take Away
    </a>
  </div>

  <form action="{{ route('kasir.search') }}" method="GET" style="display: flex; gap: 12px; margin-bottom: 24px;">
    <input type="hidden" name="tab" value="{{ $tab }}">
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Masukkan nomor pesanan atau nomor meja (Contoh: PSN-0012 atau 3)..." style="font-size: 15px;">
    <button type="submit" class="btn btn-primary btn-lg" style="white-space: nowrap;">Cari</button>
  </form>

  <!-- Search Results (Screen 44) -->
  <h4 style="font-size: 14px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 14px; letter-spacing: 0.5px;">Hasil Pencarian:</h4>

  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>No. Pesanan</th>
          <th>Meja</th>
          <th>Jenis Layanan</th>
          <th>Waktu</th>
          <th>Status</th>
          <th style="text-align: right;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($results as $res)
          <tr>
            <td style="font-weight: 800; color: var(--primary);">#{{ $res->sale_number }}</td>
            <td>{{ $res->table_number ? 'Meja ' . $res->table_number : '-' }}</td>
            <td>
              @if($res->order_type === 'dine_in')
                <span class="badge badge-blue">Dine In</span>
              @else
                <span class="badge badge-orange">Take Away</span>
              @endif
            </td>
            <td>{{ $res->created_at->format('H:i') }}</td>
            <td>
              @if($res->payment_status === 'pending')
                <span class="badge badge-orange"><span class="badge-dot"></span> Menunggu Pembayaran</span>
              @elseif($res->payment_status === 'paid')
                <span class="badge badge-green"><span class="badge-dot"></span> Lunas</span>
              @else
                <span class="badge badge-red">Dibatalkan</span>
              @endif
            </td>
            <td style="text-align: right;">
              <a href="{{ route('kasir.detail', $res->sale_number) }}" class="btn btn-secondary btn-sm">Lihat Detail</a>
              @if($res->payment_status === 'pending')
                <a href="{{ route('kasir.pay', $res->sale_number) }}" class="btn btn-primary btn-sm">Bayar</a>
              @else
                <a href="{{ route('kasir.receipt', $res->sale_number) }}" class="btn btn-secondary btn-sm">Struk</a>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
              Tidak ada data pesanan yang cocok dengan kriteria pencarian Anda.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($results->hasPages())
    <div style="margin-top: 20px;">
      {{ $results->links() }}
    </div>
  @endif
</div>
@endsection
