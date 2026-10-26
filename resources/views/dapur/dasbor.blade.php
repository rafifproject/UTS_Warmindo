@extends('layouts.kitchen')

@section('title', 'Dashboard Antrean Dapur — Warmindo')

@section('content')
<!-- Kitchen Stat Cards (Screen 57) -->
<div class="stat-grid" style="margin-bottom: 24px;">
  <div class="stat-card">
    <div>
      <div class="stat-label">Total Masuk Hari Ini</div>
      <div class="stat-value" style="color: var(--primary);">{{ $totalMasuk }}</div>
      <span style="font-size: 11.5px; color: var(--text-muted);">Hari ini</span>
    </div>
    <div class="stat-icon orange">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Antrean Baru</div>
      <div class="stat-value" style="color: #FD7E14;">{{ $queuedOrders->count() }}</div>
      <span style="font-size: 11.5px; color: var(--text-muted);">Menunggu dimasak</span>
    </div>
    <div class="stat-icon orange" style="background-color: #FFF4E6; color: #FD7E14;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Sedang Dimasak</div>
      <div class="stat-value" style="color: #F59F00;">{{ $preparingOrders->count() }}</div>
      <span style="font-size: 11.5px; color: var(--text-muted);">Di atas kompor</span>
    </div>
    <div class="stat-icon" style="background-color: #FFF9DB; color: #F59F00;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Siap Disajikan</div>
      <div class="stat-value" style="color: #0CA678;">{{ $readyOrders->count() }}</div>
      <span style="font-size: 11.5px; color: var(--text-muted);">Tunggu diantar</span>
    </div>
    <div class="stat-icon green">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
  </div>
</div>

<!-- Header Control & Switcher Bar -->
<div class="card" style="padding: 16px 20px; margin-bottom: 24px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
  <!-- Search -->
  <form action="{{ route('kitchen.dashboard') }}" method="GET" style="display: flex; gap: 10px; flex: 1; max-width: 480px;">
    <input type="hidden" name="view" value="{{ $viewMode }}">
    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari no meja / no pesanan..." style="flex: 1;">
    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
    @if(request('search'))
      <a href="{{ route('kitchen.dashboard', ['view' => $viewMode]) }}" class="btn btn-secondary btn-sm">Reset</a>
    @endif
  </form>

  <!-- View Switcher & Auto-refresh -->
  <div style="display: flex; align-items: center; gap: 10px;">
    <span style="font-size: 12.5px; color: var(--text-muted); font-weight: 600;">Tampilan:</span>
    <div style="display: inline-flex; border: 1px solid var(--border-light); border-radius: 8px; overflow: hidden;">
      <a href="{{ route('kitchen.dashboard', array_merge(request()->except('view'), ['view' => 'board'])) }}"
         class="btn btn-sm"
         style="border-radius: 0; padding: 6px 14px; font-weight: 700; {{ $viewMode === 'board' ? 'background: var(--primary); color: #fff;' : 'background: #fff; color: var(--text-dark);' }}">
        📋 3 Kolom KDS
      </a>
      <a href="{{ route('kitchen.dashboard', array_merge(request()->except('view'), ['view' => 'table'])) }}"
         class="btn btn-sm"
         style="border-radius: 0; padding: 6px 14px; font-weight: 700; border-left: 1px solid var(--border-light); {{ $viewMode === 'table' ? 'background: var(--primary); color: #fff;' : 'background: #fff; color: var(--text-dark);' }}">
        📑 Tabel
      </a>
    </div>

    <button type="button" onclick="window.location.reload();" class="btn btn-secondary btn-sm" title="Segarkan Data Sekarang">
      🔄 Refresh
    </button>
  </div>
</div>

@if($viewMode === 'board')
  <!-- ========================================== -->
  <!-- 3 KOLOM KATEGORI ANTREAN DAPUR (KDS KANBAN) -->
  <!-- ========================================== -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; align-items: start;">
    
    <!-- KOLOM 1: ANTREAN BARU -->
    <div style="background: #FFF9F3; border: 1.5px solid #FFE3C6; border-radius: 14px; padding: 18px; min-height: 520px; display: flex; flex-direction: column;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <span style="width: 12px; height: 12px; border-radius: 50%; background: #FD7E14; display: inline-block;"></span>
          <h3 style="font-size: 16px; font-weight: 800; color: #D9480F; margin: 0;">1. Antrean Baru</h3>
        </div>
        <span class="badge" style="background: #FD7E14; color: #FFFFFF; font-weight: 800; font-size: 13px; padding: 4px 10px; border-radius: 20px;">
          {{ $queuedOrders->count() }}
        </span>
      </div>
      <p style="font-size: 12px; color: #865230; margin: 0 0 16px 0;">Pesanan masuk yang belum dimasak</p>

      <div style="display: flex; flex-direction: column; gap: 14px; flex: 1;">
        @forelse($queuedOrders as $ord)
          <div class="kds-card" style="background: #FFFFFF; border: 1.5px solid #FFD8A8; border-radius: 12px; box-shadow: 0 4px 12px rgba(217, 72, 15, 0.08); overflow: hidden;">
            <!-- Header Kartu -->
            <div style="padding: 10px 14px; background: #FFF4E6; border-bottom: 1px solid #FFE3C6; display: flex; justify-content: space-between; align-items: center;">
              <div style="display: flex; align-items: center; gap: 8px;">
                @if($ord->order_type === 'dine_in')
                  <span class="badge badge-blue" style="font-size: 13px; font-weight: 800; padding: 4px 10px;">
                    MEJA {{ $ord->table_number }}
                  </span>
                @else
                  <span class="badge badge-orange" style="font-size: 13px; font-weight: 800; padding: 4px 10px;">
                    TAKE AWAY
                  </span>
                @endif
                <span style="font-size: 12px; font-weight: 700; color: #D9480F;">
                  #{{ $ord->sale_number }}
                </span>
              </div>
              <span style="font-size: 12px; font-weight: 700; color: var(--text-muted);">
                ⏱ {{ $ord->paid_at ? $ord->paid_at->format('H:i') : $ord->created_at->format('H:i') }}
              </span>
            </div>

            <!-- Isi Menu Masakan -->
            <div style="padding: 14px;">
              <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
                @foreach($ord->itemPenjualan as $item)
                  <li style="font-size: 13.5px; line-height: 1.4;">
                    <div style="display: flex; align-items: baseline; gap: 6px;">
                      <span style="font-weight: 800; color: #D9480F; font-size: 15px; min-width: 26px;">{{ $item->quantity }}x</span>
                      <span style="font-weight: 700; color: var(--text-dark);">{{ $item->product_name }}</span>
                    </div>
                    @if($item->notes)
                      <div style="margin-left: 32px; font-size: 12px; color: #D9480F; font-weight: 600; background: #FFF5F5; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-top: 2px;">
                        📌 {{ $item->notes }}
                      </div>
                    @endif
                  </li>
                @endforeach
              </ul>

              @if($ord->notes)
                <div style="margin-top: 10px; padding: 8px 10px; background: #FFF9DB; border-left: 3px solid #F59F00; border-radius: 4px; font-size: 12px; color: #744210; font-weight: 600;">
                  ⚠️ Catatan: {{ $ord->notes }}
                </div>
              @endif
            </div>

            <!-- Tombol Aksi 1-Klik -->
            <div style="padding: 10px 14px; background: #FAF7F2; border-top: 1px solid #FFE3C6; display: flex; gap: 8px;">
              <form action="{{ route('kitchen.status.update', $ord->sale_number) }}" method="POST" style="flex: 1; margin: 0;">
                @csrf
                <input type="hidden" name="status" value="preparing">
                <button type="submit" class="btn btn-sm" style="width: 100%; font-weight: 800; background: #FD7E14; border-color: #FD7E14; color: #FFFFFF; padding: 8px 12px;">
                  Mulai Masak ➔
                </button>
              </form>
              <a href="{{ route('kitchen.detail', $ord->sale_number) }}" class="btn btn-secondary btn-sm" style="padding: 8px 10px;" title="Lihat Detail Lengkap">
                Detail
              </a>
            </div>
          </div>
        @empty
          <div style="text-align: center; padding: 40px 16px; color: #865230; background: rgba(255,255,255,0.6); border-radius: 10px; border: 1px dashed #FFE3C6;">
            <div style="font-size: 32px; margin-bottom: 6px;">✨</div>
            <strong style="font-size: 13.5px; display: block;">Tidak Ada Antrean Baru</strong>
            <span style="font-size: 12px; opacity: 0.8;">Semua pesanan baru sudah dimasak</span>
          </div>
        @endforelse
      </div>
    </div>

    <!-- KOLOM 2: SEDANG DIMASAK -->
    <div style="background: #FFFDF0; border: 1.5px solid #FFEC99; border-radius: 14px; padding: 18px; min-height: 520px; display: flex; flex-direction: column;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <span style="width: 12px; height: 12px; border-radius: 50%; background: #F59F00; display: inline-block;"></span>
          <h3 style="font-size: 16px; font-weight: 800; color: #B07204; margin: 0;">2. Sedang Dimasak</h3>
        </div>
        <span class="badge" style="background: #F59F00; color: #FFFFFF; font-weight: 800; font-size: 13px; padding: 4px 10px; border-radius: 20px;">
          {{ $preparingOrders->count() }}
        </span>
      </div>
      <p style="font-size: 12px; color: #7B5003; margin: 0 0 16px 0;">Sedang disiapkan di atas kompor</p>

      <div style="display: flex; flex-direction: column; gap: 14px; flex: 1;">
        @forelse($preparingOrders as $ord)
          <div class="kds-card" style="background: #FFFFFF; border: 1.5px solid #FFE066; border-radius: 12px; box-shadow: 0 4px 12px rgba(245, 159, 0, 0.08); overflow: hidden;">
            <!-- Header Kartu -->
            <div style="padding: 10px 14px; background: #FFF9DB; border-bottom: 1px solid #FFEC99; display: flex; justify-content: space-between; align-items: center;">
              <div style="display: flex; align-items: center; gap: 8px;">
                @if($ord->order_type === 'dine_in')
                  <span class="badge badge-blue" style="font-size: 13px; font-weight: 800; padding: 4px 10px;">
                    MEJA {{ $ord->table_number }}
                  </span>
                @else
                  <span class="badge badge-orange" style="font-size: 13px; font-weight: 800; padding: 4px 10px;">
                    TAKE AWAY
                  </span>
                @endif
                <span style="font-size: 12px; font-weight: 700; color: #B07204;">
                  #{{ $ord->sale_number }}
                </span>
              </div>
              <span class="badge" style="background: #FFF3BF; color: #B07204; font-weight: 700; font-size: 11px;">
                🔥 Memasak
              </span>
            </div>

            <!-- Isi Menu Masakan -->
            <div style="padding: 14px;">
              <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
                @foreach($ord->itemPenjualan as $item)
                  <li style="font-size: 13.5px; line-height: 1.4;">
                    <div style="display: flex; align-items: baseline; gap: 6px;">
                      <span style="font-weight: 800; color: #F59F00; font-size: 15px; min-width: 26px;">{{ $item->quantity }}x</span>
                      <span style="font-weight: 700; color: var(--text-dark);">{{ $item->product_name }}</span>
                    </div>
                    @if($item->notes)
                      <div style="margin-left: 32px; font-size: 12px; color: #D9480F; font-weight: 600; background: #FFF5F5; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-top: 2px;">
                        📌 {{ $item->notes }}
                      </div>
                    @endif
                  </li>
                @endforeach
              </ul>

              @if($ord->notes)
                <div style="margin-top: 10px; padding: 8px 10px; background: #FFF9DB; border-left: 3px solid #F59F00; border-radius: 4px; font-size: 12px; color: #744210; font-weight: 600;">
                  ⚠️ Catatan: {{ $ord->notes }}
                </div>
              @endif
            </div>

            <!-- Tombol Aksi 1-Klik -->
            <div style="padding: 10px 14px; background: #FAF7F2; border-top: 1px solid #FFEC99; display: flex; gap: 8px;">
              <form action="{{ route('kitchen.status.update', $ord->sale_number) }}" method="POST" style="flex: 1; margin: 0;">
                @csrf
                <input type="hidden" name="status" value="ready_to_serve">
                <button type="submit" class="btn btn-sm" style="width: 100%; font-weight: 800; background: #0CA678; border-color: #0CA678; color: #FFFFFF; padding: 8px 12px;">
                  Siap Disajikan ✔
                </button>
              </form>
              <a href="{{ route('kitchen.detail', $ord->sale_number) }}" class="btn btn-secondary btn-sm" style="padding: 8px 10px;" title="Lihat Detail Lengkap">
                Detail
              </a>
            </div>
          </div>
        @empty
          <div style="text-align: center; padding: 40px 16px; color: #7B5003; background: rgba(255,255,255,0.6); border-radius: 10px; border: 1px dashed #FFEC99;">
            <div style="font-size: 32px; margin-bottom: 6px;">🍳</div>
            <strong style="font-size: 13.5px; display: block;">Kompor Bersih</strong>
            <span style="font-size: 12px; opacity: 0.8;">Tidak ada pesanan yang sedang dimasak</span>
          </div>
        @endforelse
      </div>
    </div>

    <!-- KOLOM 3: SIAP DISAJIKAN -->
    <div style="background: #F4FDF9; border: 1.5px solid #C3FAE8; border-radius: 14px; padding: 18px; min-height: 520px; display: flex; flex-direction: column;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <span style="width: 12px; height: 12px; border-radius: 50%; background: #0CA678; display: inline-block;"></span>
          <h3 style="font-size: 16px; font-weight: 800; color: #087F5B; margin: 0;">3. Siap Disajikan</h3>
        </div>
        <span class="badge" style="background: #0CA678; color: #FFFFFF; font-weight: 800; font-size: 13px; padding: 4px 10px; border-radius: 20px;">
          {{ $readyOrders->count() }}
        </span>
      </div>
      <p style="font-size: 12px; color: #086146; margin: 0 0 16px 0;">Menunggu pelayan mengantar ke meja</p>

      <div style="display: flex; flex-direction: column; gap: 14px; flex: 1;">
        @forelse($readyOrders as $ord)
          <div class="kds-card" style="background: #FFFFFF; border: 1.5px solid #96F2D7; border-radius: 12px; box-shadow: 0 4px 12px rgba(12, 166, 120, 0.08); overflow: hidden;">
            <!-- Header Kartu -->
            <div style="padding: 10px 14px; background: #E6FCF5; border-bottom: 1px solid #C3FAE8; display: flex; justify-content: space-between; align-items: center;">
              <div style="display: flex; align-items: center; gap: 8px;">
                @if($ord->order_type === 'dine_in')
                  <span class="badge badge-blue" style="font-size: 13px; font-weight: 800; padding: 4px 10px;">
                    MEJA {{ $ord->table_number }}
                  </span>
                @else
                  <span class="badge badge-orange" style="font-size: 13px; font-weight: 800; padding: 4px 10px;">
                    TAKE AWAY
                  </span>
                @endif
                <span style="font-size: 12px; font-weight: 700; color: #087F5B;">
                  #{{ $ord->sale_number }}
                </span>
              </div>
              <span class="badge" style="background: #C3FAE8; color: #087F5B; font-weight: 700; font-size: 11px;">
                🔔 Siap Antar
              </span>
            </div>

            <!-- Isi Menu Masakan -->
            <div style="padding: 14px;">
              <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
                @foreach($ord->itemPenjualan as $item)
                  <li style="font-size: 13.5px; line-height: 1.4;">
                    <div style="display: flex; align-items: baseline; gap: 6px;">
                      <span style="font-weight: 800; color: #0CA678; font-size: 15px; min-width: 26px;">{{ $item->quantity }}x</span>
                      <span style="font-weight: 700; color: var(--text-dark);">{{ $item->product_name }}</span>
                    </div>
                  </li>
                @endforeach
              </ul>
            </div>

            <!-- Tombol Aksi 1-Klik -->
            <div style="padding: 10px 14px; background: #FAF7F2; border-top: 1px solid #C3FAE8; display: flex; gap: 8px;">
              <form action="{{ route('kitchen.status.update', $ord->sale_number) }}" method="POST" style="flex: 1; margin: 0;">
                @csrf
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="btn btn-sm" style="width: 100%; font-weight: 800; background: var(--primary); border-color: var(--primary); color: #FFFFFF; padding: 8px 12px;">
                  Selesai Diantar 🏁
                </button>
              </form>
              <a href="{{ route('kitchen.detail', $ord->sale_number) }}" class="btn btn-secondary btn-sm" style="padding: 8px 10px;" title="Lihat Detail Lengkap">
                Detail
              </a>
            </div>
          </div>
        @empty
          <div style="text-align: center; padding: 40px 16px; color: #086146; background: rgba(255,255,255,0.6); border-radius: 10px; border: 1px dashed #C3FAE8;">
            <div style="font-size: 32px; margin-bottom: 6px;">🍽️</div>
            <strong style="font-size: 13.5px; display: block;">Semua Sudah Diantar</strong>
            <span style="font-size: 12px; opacity: 0.8;">Tidak ada pesanan yang menunggu diambil</span>
          </div>
        @endforelse
      </div>
    </div>

  </div>

@else
  <!-- ========================================== -->
  <!-- TAMPILAN TABEL BIASA (JIKA DIPILIH)        -->
  <!-- ========================================== -->
  <div class="card" style="padding: 0; overflow: hidden;">
    <div style="padding: 16px 24px; border-bottom: 1px solid var(--border-light); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
      <form action="{{ route('kitchen.dashboard') }}" method="GET" style="display: flex; gap: 10px; flex: 1; max-width: 700px;">
        <input type="hidden" name="view" value="table">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nomor pesanan / meja..." style="flex: 2;">
        <select name="status" class="form-control form-select" style="flex: 1.2;">
          <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
          <option value="queued" {{ request('status') === 'queued' ? 'selected' : '' }}>Antrean Masuk (Queued)</option>
          <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Sedang Diproses</option>
          <option value="ready_to_serve" {{ request('status') === 'ready_to_serve' ? 'selected' : '' }}>Siap Disajikan</option>
          <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        @if(request()->hasAny(['search', 'status']))
          <a href="{{ route('kitchen.dashboard', ['view' => 'table']) }}" class="btn btn-secondary btn-sm">Reset</a>
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
@endif

<script>
  // Opsional auto-refresh setiap 25 detik agar dapur selalu update otomatis tanpa klik
  let refreshTimer = setTimeout(function() {
    window.location.reload();
  }, 25000);
</script>
@endsection
