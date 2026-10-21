<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Kasir Warmindo — Sistem Pembayaran')</title>
  <link rel="stylesheet" href="{{ asset('css/warmindo.css') }}">
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
  @stack('styles')
</head>
<body style="background-color: #FAF7F2;">
  <!-- Kasir Navbar -->
  <header style="background-color: #FFFFFF; border-bottom: 1px solid var(--border-light); padding: 12px 28px; position: sticky; top: 0; z-index: 100;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
      <div style="display: flex; align-items: center; gap: 28px;">
        <a href="{{ route('kasir.dashboard') }}">
          <img src="{{ asset('images/logo.svg') }}" alt="Warmindo Logo" style="height: 42px;">
        </a>

        <nav style="display: flex; align-items: center; gap: 8px;">
          <a href="{{ route('kasir.dashboard') }}" class="btn btn-sm {{ request()->routeIs('kasir.dashboard') ? 'btn-primary' : 'btn-secondary' }}">
            Dashboard
          </a>
          <a href="{{ route('kasir.waiting') }}" class="btn btn-sm {{ request()->routeIs('kasir.waiting') ? 'btn-primary' : 'btn-secondary' }}">
            Pesanan Menunggu
          </a>
          <a href="{{ route('kasir.search') }}" class="btn btn-sm {{ request()->routeIs('kasir.search') ? 'btn-primary' : 'btn-secondary' }}">
            Cari Pesanan
          </a>
          <a href="{{ route('kitchen.dashboard') }}" class="btn btn-sm btn-outline-primary" target="_blank">
            KDS Dapur
          </a>
        </nav>
      </div>

      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="text-align: right;">
          <span style="font-size: 13.5px; font-weight: 700; color: var(--text-dark); display: block;">Halo, {{ auth()->user()->name ?? 'Kasir' }}!</span>
          <span style="font-size: 11.5px; color: var(--text-muted);">{{ Carbon\Carbon::now()->translatedFormat('d M Y') }} &bull; <span id="kasirLiveClock"></span></span>
        </div>

        <div style="width: 36px; height: 36px; border-radius: 50%; background-color: #FFF4E6; color: var(--primary); font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 13px; border: 1.5px solid #FFD8A8;">
          {{ strtoupper(substr(auth()->user()->name ?? 'K', 0, 2)) }}
        </div>

        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
          @csrf
          <button type="submit" class="btn btn-sm btn-secondary" title="Keluar">
            Keluar
          </button>
        </form>
      </div>
    </div>
  </header>

  <!-- Flash Alerts -->
  <div class="container-fluid" style="margin-top: 20px;">
    @if(session('success'))
      <div style="background-color: #E6FCF5; border-left: 4px solid #12B886; color: #0CA678; padding: 12px 18px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
        <span>{{ session('success') }}</span>
        <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 18px; cursor: pointer; color: inherit;">&times;</button>
      </div>
    @endif

    @if(session('error'))
      <div style="background-color: #FFE3E3; border-left: 4px solid #FA5252; color: #E03131; padding: 12px 18px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
        <span>{{ session('error') }}</span>
        <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 18px; cursor: pointer; color: inherit;">&times;</button>
      </div>
    @endif
  </div>

  <main class="container-fluid" style="padding-top: 10px; padding-bottom: 40px;">
    @yield('content')
  </main>

  <script src="{{ asset('js/warmindo.js') }}"></script>
  <script>
    function updateClock() {
      const now = new Date();
      const str = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
      const elem = document.getElementById('kasirLiveClock');
      if (elem) elem.textContent = str;
    }
    updateClock();
    setInterval(updateClock, 1000);
  </script>
  @stack('scripts')
</body>
</html>
