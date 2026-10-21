@extends('layouts.kitchen')

@section('title', 'Riwayat Status Pesanan — Dapur Warmindo')

@section('content')
<div class="card" style="padding: 0; overflow: hidden;">
  <!-- Filter Bar (Screen 60) -->
  <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-light); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
    <div>
      <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark);">Riwayat Status Pesanan</h2>
      <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Log audit pergerakan status pesanan dari pembayaran hingga selesai disajikan.</p>
    </div>

    <form action="{{ route('kitchen.history') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
      <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" style="width: 140px;">
      <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" style="width: 140px;">
      <select name="status" class="form-control form-select" style="width: 160px;">
        <option value="all">Semua Status</option>
        <option value="queued" {{ request('status') === 'queued' ? 'selected' : '' }}>Antrean Masuk</option>
        <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Diproses</option>
        <option value="ready_to_serve" {{ request('status') === 'ready_to_serve' ? 'selected' : '' }}>Siap Disajikan</option>
        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
      </select>
      <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="No. Pesanan / Meja..." style="width: 180px;">
      <button type="submit" class="btn btn-primary">Filter</button>
      @if(request()->hasAny(['start_date', 'end_date', 'status', 'search']))
        <a href="{{ route('kitchen.history') }}" class="btn btn-secondary">Reset</a>
      @endif
    </form>
  </div>

  <!-- Audit Table (Screen 60) -->
  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px;">No. Pesanan</th>
          <th>Menu</th>
          <th>Status Masakan</th>
          <th>Waktu Perubahan</th>
          <th>Petugas</th>
          <th>Keterangan</th>
        </tr>
      </thead>
      <tbody>
        @forelse($histories as $h)
          <tr>
            <td style="padding-left: 24px; font-weight: 800; color: var(--primary);">
              #{{ $h->sale->sale_number ?? '-' }}
            </td>
            <td style="max-width: 260px; font-weight: 600;">
              {{ $h->sale->items_summary ?? '-' }}
            </td>
            <td>
              @if($h->to_status === 'queued')
                <span class="badge badge-orange"><span class="badge-dot"></span> Antrean Baru</span>
              @elseif($h->to_status === 'preparing')
                <span class="badge badge-yellow"><span class="badge-dot"></span> Diproses</span>
              @elseif($h->to_status === 'ready_to_serve')
                <span class="badge badge-green"><span class="badge-dot"></span> Siap Disajikan</span>
              @elseif($h->to_status === 'completed')
                <span class="badge badge-blue"><span class="badge-dot"></span> Selesai</span>
              @else
                <span class="badge badge-red">{{ $h->to_status }}</span>
              @endif
            </td>
            <td style="color: var(--text-dark); font-size: 13px;">
              {{ $h->created_at->format('d M Y, H:i:s') }}
            </td>
            <td style="color: var(--text-body);">
              {{ $h->staff_name }}
            </td>
            <td style="font-size: 12.5px; color: var(--text-muted);">
              {{ $h->notes ?? '-' }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
              Belum ada riwayat perubahan status pesanan.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($histories->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
      <span style="font-size: 13px; color: var(--text-muted);">Menampilkan {{ $histories->firstItem() }} - {{ $histories->lastItem() }} dari {{ $histories->total() }} riwayat</span>
      {{ $histories->links() }}
    </div>
  @endif
</div>
@endsection
