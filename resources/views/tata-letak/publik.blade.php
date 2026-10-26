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
      <div style="background-color: #E6FCF5; border-left: 4px solid #12B886; color: #0CA678; padding: 14px 20px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 14px; box-shadow: 0 4px 12px rgba(18, 184, 134, 0.12);">
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
          <span style="font-size: 18px;">✅</span>
          <span>{{ session('success') }}</span>
        </div>
        <div style="display: flex; align-items: center; gap: 12px; margin-left: auto;">
          @if(!request()->routeIs('cart.*'))
            <a href="{{ route('cart.index') }}" class="btn btn-sm" style="background-color: #12B886; color: #FFFFFF; font-weight: 700; padding: 6px 14px; border-radius: 6px; text-decoration: none; white-space: nowrap;">
              Lihat Keranjang &rarr;
            </a>
          @endif
          <button type="button" onclick="this.closest('div[style*=\'background-color\']').remove()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: inherit; line-height: 1;">&times;</button>
        </div>
      </div>
    @endif

    @if(session('error'))
      <div style="background-color: #FFE3E3; border-left: 4px solid #FA5252; color: #E03131; padding: 14px 20px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 14px; box-shadow: 0 4px 12px rgba(224, 49, 49, 0.12);">
        <div style="display: flex; align-items: center; gap: 10px;">
          <span style="font-size: 18px;">⚠️</span>
          <span>{{ session('error') }}</span>
        </div>
        <button type="button" onclick="this.closest('div[style*=\'background-color\']').remove()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: inherit; line-height: 1;">&times;</button>
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

  <!-- Floating Sticky Cart Bar for Easy Multi-Item Ordering -->
  @php
    $floatingCart = session('cart', []);
    $floatingCount = array_sum(array_column($floatingCart, 'quantity'));
    $floatingTotal = 0;
    foreach ($floatingCart as $fcItem) {
        $floatingTotal += $fcItem['price'] * $fcItem['quantity'];
    }
  @endphp

  @if($floatingCount > 0 && !request()->routeIs('cart.*'))
    <div id="floatingCartBar" style="position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%); z-index: 1000; width: calc(100% - 32px); max-width: 520px; background: #1A1A1A; color: #FFFFFF; padding: 12px 20px; border-radius: 999px; box-shadow: 0 12px 32px rgba(0,0,0,0.35); display: flex; justify-content: space-between; align-items: center; gap: 14px; border: 1.5px solid rgba(255,255,255,0.15); backdrop-filter: blur(8px);">
      <div style="display: flex; align-items: center; gap: 12px;">
        <div style="background-color: var(--primary); color: #FFFFFF; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; box-shadow: 0 2px 6px rgba(224,49,49,0.4);">
          {{ $floatingCount }}
        </div>
        <div>
          <div style="font-size: 11px; color: #ADB5BD; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Pesanan Anda</div>
          <div style="font-size: 15px; font-weight: 800; color: #FFFFFF;">Rp {{ number_format($floatingTotal, 0, ',', '.') }}</div>
        </div>
      </div>
      <a href="{{ route('cart.index') }}" class="btn btn-primary btn-sm" style="border-radius: 999px; padding: 8px 18px; font-weight: 700; text-decoration: none; box-shadow: 0 4px 12px rgba(224,49,49,0.4); display: flex; align-items: center; gap: 6px;">
        <span>Buka Keranjang</span>
        <span>&rarr;</span>
      </a>
    </div>
  @endif

  <script src="{{ asset('js/warmindo.js') }}"></script>
  @stack('scripts')
</body>
</html>
