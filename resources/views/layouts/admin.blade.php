<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Warmindo — Sistem Operasional & Owner Dashboard')</title>
  <link rel="stylesheet" href="{{ asset('css/warmindo.css') }}">
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
  <!-- Chart.js for Interactive Visual Reports -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  @stack('styles')
</head>
<body>
  <div class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
      <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}">
          <img src="{{ asset('images/logo-white.svg') }}" alt="Warmindo Logo" style="height: 42px;">
        </a>
      </div>

      <ul class="sidebar-menu">
        <li class="sidebar-header">UTAMA</li>
        <li class="sidebar-item">
          <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
            </svg>
            <span>Dashboard</span>
          </a>
        </li>

        <li class="sidebar-header">MASTER DATA</li>
        <li class="sidebar-item">
          <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <span>Kategori Menu</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            <span>Produk & Persediaan</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="{{ route('admin.suppliers.index') }}" class="sidebar-link {{ request()->routeIs('admin.suppliers*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
            </svg>
            <span>Supplier</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <span>Pengguna & Role</span>
          </a>
        </li>

        <li class="sidebar-header">TRANSAKSI</li>
        <li class="sidebar-item">
          <a href="{{ route('admin.purchases.index') }}" class="sidebar-link {{ request()->routeIs('admin.purchases*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            <span>Pembelian / Stok Masuk</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="{{ route('admin.sales.index') }}" class="sidebar-link {{ request()->routeIs('admin.sales*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
            <span>Penjualan & Transaksi</span>
          </a>
        </li>

        <li class="sidebar-header">LAPORAN & AI</li>
        <li class="sidebar-item">
          <a href="{{ route('admin.reports.sales') }}" class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
            </svg>
            <span>Laporan Interaktif</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="{{ route('admin.ai.index') }}" class="sidebar-link {{ request()->routeIs('admin.ai*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="22"/>
            </svg>
            <span>AI Business Assistant</span>
          </a>
        </li>

        <li class="sidebar-header">OPERASIONAL OUTLET</li>
        <li class="sidebar-item">
          <a href="{{ route('kasir.dashboard') }}" class="sidebar-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
            </svg>
            <span>Buka Halaman Kasir</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="{{ route('kitchen.dashboard') }}" class="sidebar-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 10h-1.26A8 8 0 1 0 9 20h9a4 4 0 0 0 0-8z"/>
            </svg>
            <span>Antrean Dapur (KDS)</span>
          </a>
        </li>
      </ul>

      <!-- Sidebar User Footer -->
      <div class="sidebar-footer">
        <div class="sidebar-user-avatar">
          {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
        </div>
        <div class="sidebar-user-info" style="flex: 1; overflow: hidden;">
          <p style="font-size: 13.5px; font-weight: 700; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">{{ auth()->user()->name ?? 'Admin' }}</p>
          <p style="font-size: 11.5px; opacity: 0.65; text-transform: uppercase;">{{ auth()->user()->role ?? 'Admin' }}</p>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
          @csrf
          <button type="submit" title="Keluar" style="background: none; border: none; color: #FFA940; cursor: pointer; padding: 4px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
          </button>
        </form>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-main">
      <header class="admin-topbar">
        <div>
          <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark);">@yield('page_title', 'Dashboard')</h2>
          <p style="font-size: 12.5px; color: var(--text-muted);">Sistem Informasi Warmindo &bull; {{ Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
        </div>

        <div style="display: flex; align-items: center; gap: 16px;">
          <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-secondary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            Lihat Katalog Publik
          </a>
          <div style="display: flex; align-items: center; gap: 8px;">
            <span class="badge badge-green">● Online</span>
          </div>
        </div>
      </header>

      <div class="admin-content">
        <!-- Flash messages -->
        @if(session('success'))
          <div style="background-color: #E6FCF5; border-left: 4px solid #12B886; color: #0CA678; padding: 12px 18px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
            <span>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 18px; cursor: pointer; color: inherit;">&times;</button>
          </div>
        @endif

        @if(session('error'))
          <div style="background-color: #FFE3E3; border-left: 4px solid #FA5252; color: #E03131; padding: 12px 18px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
            <span>{{ session('error') }}</span>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 18px; cursor: pointer; color: inherit;">&times;</button>
          </div>
        @endif

        @yield('content')
      </div>
    </div>
  </div>

  <script src="{{ asset('js/warmindo.js') }}"></script>
  @stack('scripts')
</body>
</html>
