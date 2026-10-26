<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dapur Warmindo — Antrean & Status Masakan')</title>
  <link rel="stylesheet" href="{{ asset('css/warmindo.css') }}">
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
  @stack('styles')
</head>
<body>
  <div class="admin-layout">
    <!-- Kitchen Deep Green Sidebar -->
    <aside class="admin-sidebar">
      <div class="sidebar-brand">
        <a href="{{ route('kitchen.dashboard') }}">
          <img src="{{ asset('images/logo-white.svg') }}" alt="Warmindo Logo" style="height: 42px;">
        </a>
      </div>

      <ul class="sidebar-menu">
        <li class="sidebar-header">ANTREAN DAPUR</li>
        <li class="sidebar-item">
          <a href="{{ route('kitchen.dashboard') }}" class="sidebar-link {{ request()->routeIs('kitchen.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
            <span>Antrean Masakan</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="{{ route('kitchen.dashboard', ['status' => 'completed']) }}" class="sidebar-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <span>Pesanan Selesai</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="{{ route('kitchen.history') }}" class="sidebar-link {{ request()->routeIs('kitchen.history') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>Riwayat Status</span>
          </a>
        </li>

        <li class="sidebar-header">NAVIGASI SISTEM</li>
        <li class="sidebar-item">
          <a href="{{ route('kasir.dashboard') }}" class="sidebar-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
            <span>Buka Kasir</span>
          </a>
        </li>
        @if(auth()->check() && auth()->user()->isAdmin())
        <li class="sidebar-item">
          <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
            <span>Admin Owner</span>
          </a>
        </li>
        @endif
      </ul>

      <div class="sidebar-footer">
        <div class="sidebar-user-avatar" style="background-color: #12B886;">
          D
        </div>
        <div class="sidebar-user-info" style="flex: 1;">
          <p style="font-size: 13.5px; font-weight: 700;">Petugas Dapur</p>
          <p style="font-size: 11.5px; color: #51CF66;">● Online Siap Masak</p>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
          @csrf
          <button type="submit" title="Keluar" style="background: none; border: none; color: #FFA940; cursor: pointer;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          </button>
        </form>
      </div>
    </aside>

    <!-- Kitchen Main Area -->
    <div class="admin-main">
      <header class="admin-topbar">
        <div>
          <h2 style="font-size: 18px; font-weight: 800; color: var(--text-dark);">Antrean Dapur (KDS)</h2>
          <p style="font-size: 12.5px; color: var(--text-muted);">Pesanan yang sudah dibayar dan siap diproses &bull; {{ Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
        </div>

        <div style="display: flex; align-items: center; gap: 16px;">
          <div style="font-size: 15px; font-weight: 700; color: var(--primary);" id="kitchenLiveClock"></div>
          <button onclick="window.location.reload()" class="btn btn-sm btn-secondary" title="Refresh Antrean">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
            Segarkan
          </button>
        </div>
      </header>

      <div class="admin-content">
        @if(session('success'))
          <div style="background-color: #E6FCF5; border-left: 4px solid #12B886; color: #0CA678; padding: 12px 18px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 24px;">
            {{ session('success') }}
          </div>
        @endif

        @yield('content')
      </div>
    </div>
  </div>

  <script src="{{ asset('js/warmindo.js') }}"></script>
  <script>
    function updateClock() {
      const now = new Date();
      const str = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
      const elem = document.getElementById('kitchenLiveClock');
      if (elem) elem.textContent = str;
    }
    updateClock();
    setInterval(updateClock, 1000);
  </script>
  @stack('scripts')
</body>
</html>
