<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Warmindo - Makan Enak, Harga Bersahabat. Pesan mie instan, mie hangat, minuman segar, dan camilan nikmat langsung dari meja Anda.">
  <title>@yield('title', 'Warmindo — Makan Enak, Harga Bersahabat')</title>
  <link rel="stylesheet" href="{{ asset('css/warmindo.css') }}">
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
  @stack('styles')
</head>
<body>
  <!-- Public Navbar -->
  <nav class="pub-navbar">
    <div class="container pub-nav-container">
      <a href="{{ route('home') }}" class="d-flex align-items-center">
        <img src="{{ asset('images/logo.svg') }}" alt="Warmindo Logo" style="height: 48px;">
      </a>

      <div class="pub-nav-links">
        <a href="{{ route('home') }}" class="pub-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
        <a href="{{ route('menu') }}" class="pub-nav-link {{ request()->routeIs('menu*') ? 'active' : '' }}">Menu</a>
      </div>

      <div style="display: flex; align-items: center; gap: 14px;">
        @php
          $cartCount = array_sum(array_column(session('cart', []), 'quantity'));
        @endphp
        <a href="{{ route('cart.index') }}" class="pub-cart-btn" title="Keranjang Belanja">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="8" cy="21" r="1"></circle>
            <circle cx="19" cy="21" r="1"></circle>
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
          </svg>
          @if($cartCount > 0)
            <span class="pub-cart-badge">{{ $cartCount }}</span>
          @endif
        </a>

        @auth
          @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-secondary">Dashboard Owner</a>
          @else
            <a href="{{ route('kasir.dashboard') }}" class="btn btn-sm btn-secondary">Kasir</a>
          @endif
        @else
          <a href="{{ route('login') }}" class="btn btn-sm btn-secondary">Masuk Kasir</a>
        @endauth
      </div>
    </div>
  </nav>

  <!-- Flash Alerts -->
  <div class="container" style="margin-top: 20px;">
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

  <!-- Main Content -->
  <main>
    @yield('content')
  </main>

  <!-- Footer -->
  <footer style="background-color: #FFFFFF; border-top: 1px solid var(--border-light); padding: 40px 0; margin-top: 60px;">
    <div class="container" style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px;">
      <div>
        <img src="{{ asset('images/logo.svg') }}" alt="Warmindo Logo" style="height: 42px; margin-bottom: 8px;">
        <p style="font-size: 13.5px; color: var(--text-muted);">Sistem Informasi Pemesanan & Operasional Warmindo Terintegrasi</p>
      </div>
      <div style="text-align: right;">
        <p style="font-size: 14px; font-weight: 700; color: var(--primary);">Warmindo — Lebih dari Sekadar Mie</p>
        <p style="font-size: 13px; color: var(--text-muted);">&copy; {{ date('Y') }} UTS Pemrograman Web 2. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script src="{{ asset('js/warmindo.js') }}"></script>
  @stack('scripts')
</body>
</html>
