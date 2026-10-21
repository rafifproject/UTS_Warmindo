@extends('layouts.kitchen')

@section('title', 'Dashboard Antrean Dapur — Warmindo')

@section('content')
<!-- Kitchen Stat Cards (Screen 57) -->
<div class="stat-grid" style="margin-bottom: 24px;">
  <div class="stat-card">
    <div>
      <div class="stat-label">Total Pesanan Masuk</div>
      <div class="stat-value" style="color: var(--primary);">{{ $totalMasuk }}</div>
      <span style="font-size: 11.5px; color: var(--text-muted);">Hari ini</span>
    </div>
    <div class="stat-icon orange">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Sedang Diproses</div>
      <div class="stat-value" style="color: #0CA678;">{{ $sedangDiproses }}</div>
      <span style="font-size: 11.5px; color: var(--text-muted);">Pesanan aktif</span>
    </div>
    <div class="stat-icon green">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Siap Disajikan</div>
      <div class="stat-value" style="color: #F59F00;">{{ $siapDisajikan }}</div>
      <span style="font-size: 11.5px; color: var(--text-muted);">Menunggu diantar</span>
    </div>
    <div class="stat-icon" style="background-color: #FFF9DB; color: #F59F00;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a4 4 0 0 0 0-8z"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Selesai</div>
      <div class="stat-value" style="color: #1C7ED6;">{{ $selesai }}</div>
      <span style="font-size: 11.5px; color: var(--text-muted);">Tuntas disajikan</span>
    </div>
    <div class="stat-icon blue">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>
  </div>
</div>

<!-- Queue List Card (Screen 57) -->
<div class="card" style="padding: 0; overflow: hidden;">
  <!-- Filter Toolbar -->
  <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-light); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
    <form action="{{ route('kitchen.dashboard') }}" method="GET" style="display: flex; gap: 10px; flex: 1; max-width: 700px;">
      <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nomor pesanan / meja..." style="flex: 2;">
      <select name="status" class="form-control form-select" style="flex: 1.2;">
        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
        <option value="queued" {{ request('status') === 'queued' ? 'selected' : '' }}>Antrean Masuk (Queued)</option>
        <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Sedang Diproses</option>
        <option value="ready_to_serve" {{ request('status') === 'ready_to_serve' ? 'selected' : '' }}>Siap Disajikan</option>
        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
      </select>
      <select name="sort" class="form-control form-select" style="flex: 1.2;">
        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Urutkan Terbaru</option>
        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Urutkan Terlama (Prioritas)</option>
      </select>
      <button type="submit" class="btn btn-primary">Filter</button>
      @if(request()->hasAny(['search', 'status', 'sort']))
        <a href="{{ route('kitchen.dashboard') }}" class="btn btn-secondary">Reset</a>
      @endif
    </form>
  </div>

  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px;">No. Pesanan</th>
          <th>Meja / Layanan</th>
          <th>Menu</th>
          <th style="text-align: center;">Jumlah Item</th>
          <th>Status Masakan</th>
          <th>Waktu Masuk</th>
          <th style="text-align: right; padding-right: 24px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $ord)
          <tr>
            <td style="padding-left: 24px; font-weight: 800; color: var(--primary);">
              #{{ $ord->sale_number }}
            </td>
            <td>
              @if($ord->order_type === 'dine_in')
                <span class="badge badge-blue">Meja {{ $ord->table_number }}</span>
              @else
                <span class="badge badge-orange">Take Away</span>
              @endif
            </td>
            <td style="max-width: 280px; font-weight: 600; color: var(--text-dark);">
              {{ $ord->items_summary }}
              @if($ord->notes)
                <div style="font-size: 11.5px; color: var(--primary); font-weight: normal; margin-top: 2px;">
                  Catatan: {{ $ord->notes }}
                </div>
              @endif
            </td>
            <td style="text-align: center; font-weight: 700;">
              {{ $ord->items->sum('quantity') }}
            </td>
            <td>
              @if($ord->order_status === 'queued')
                <span class="badge badge-orange"><span class="badge-dot"></span> Antrean Baru</span>
              @elseif($ord->order_status === 'preparing')
                <span class="badge badge-yellow"><span class="badge-dot"></span> Sedang Diproses</span>
              @elseif($ord->order_status === 'ready_to_serve')
                <span class="badge badge-green"><span class="badge-dot"></span> Siap Disajikan</span>
              @elseif($ord->order_status === 'completed')
                <span class="badge badge-blue"><span class="badge-dot"></span> Selesai</span>
              @endif
            </td>
            <td style="color: var(--text-muted); font-size: 13px;">
              {{ $ord->paid_at ? $ord->paid_at->format('H:i') : $ord->created_at->format('H:i') }}
            </td>
            <td style="text-align: right; padding-right: 24px;">
              <a href="{{ route('kitchen.detail', $ord->sale_number) }}" class="btn btn-primary btn-sm">
                Lihat &amp; Masak
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align: center; padding: 48px;">
              <div class="state-box" style="padding: 0;">
                <img src="{{ asset('images/empty-box.svg') }}" alt="Antrean Bersih" class="state-img" style="width: 100px; height: 100px;">
                <h4 class="state-title" style="font-size: 16px;">Antrean dapur bersih</h4>
                <p class="state-desc" style="font-size: 13px;">Tidak ada pesanan aktif yang perlu dimasak saat ini.</p>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($orders->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
      <span style="font-size: 13px; color: var(--text-muted);">Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }} pesanan</span>
      {{ $orders->links() }}
    </div>
  @endif
</div>
@endsection
