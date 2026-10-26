@extends('layouts.public')

@section('title', 'Beranda — Warmindo Makan Enak, Harga Bersahabat')

@section('content')
<div class="container">
  <!-- Hero Section (Screen 50) -->
  <div class="hero-banner">
    <div style="flex: 1; z-index: 2;">
      <span class="hero-pill">
        🍜 Selamat Datang di Warmindo
      </span>
      <h1 class="hero-title">
        Lapar?<br>Mie favoritmu<br>siap menemani.
      </h1>
      <p class="hero-subtitle">
        Nikmati pilihan menu mie instan, mie hangat, minuman segar, dan camilan nikmat dengan harga bersahabat.
      </p>
      <a href="{{ route('menu') }}" class="btn btn-primary btn-lg">
        Lihat Menu &rarr;
      </a>
    </div>

    <div class="hero-img-box">
      <img src="{{ asset('images/menu/indomie-goreng.png') }}" alt="Indomie Goreng Warmindo">
    </div>
  </div>

  <!-- Menu Favorit Section (Screen 50) -->
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
      <h2 style="font-size: 22px; font-weight: 800; color: var(--text-dark);">Menu Favorit</h2>
      <p style="font-size: 13.5px; color: var(--text-muted);">Pilihan paling banyak dipesan pelanggan setia Warmindo</p>
    </div>
    <a href="{{ route('menu') }}" style="font-size: 14px; font-weight: 700; color: var(--primary);">
      Lihat Semua &rarr;
    </a>
  </div>

  <div class="menu-grid">
    @foreach($favorites as $item)
      <div class="menu-card">
        <a href="{{ route('menu.detail', $item->slug) }}">
          <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="menu-card-img">
        </a>
        <div class="menu-card-body">
          <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
            <a href="{{ route('menu.detail', $item->slug) }}" class="menu-card-title">
              {{ $item->name }}
            </a>
          </div>
          <span style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">{{ $item->category->name ?? 'Menu' }}</span>
          <div class="menu-card-price">{{ $item->formatted_sell_price }}</div>
          
          <div class="menu-card-footer">
            <form action="{{ route('cart.add', $item->id) }}" method="POST">
              @csrf
              <input type="hidden" name="quantity" value="1">
              <button type="submit" class="btn btn-primary btn-block btn-sm">
                + Tambah
              </button>
            </form>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection
