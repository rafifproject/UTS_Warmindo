@extends('layouts.public')

@section('title', 'Katalog Menu — Warmindo')

@section('content')
<div class="container" style="padding-top: 24px;">
  <!-- Search Bar (Screen 51) -->
  <div style="margin-bottom: 24px;">
    <form action="{{ route('menu') }}" method="GET" style="display: flex; gap: 12px; max-width: 680px;">
      <div style="position: relative; flex: 1;">
        <svg style="position: absolute; left: 14px; top: 12px; color: var(--text-muted);" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
        </svg>
        <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari menu favoritmu..." style="padding-left: 42px;">
      </div>
      @if($selectedCategory && $selectedCategory !== 'all')
        <input type="hidden" name="category" value="{{ $selectedCategory }}">
      @endif
      <button type="submit" class="btn btn-primary">Cari</button>
      @if($search)
        <a href="{{ route('menu', ['category' => $selectedCategory]) }}" class="btn btn-secondary">Reset</a>
      @endif
    </form>
  </div>

  <!-- Category Pills (Screen 51) -->
  <div class="cat-pill-group">
    <a href="{{ route('menu', ['search' => $search]) }}" class="cat-pill {{ $selectedCategory === 'all' || !$selectedCategory ? 'active' : '' }}">
      Semua
    </a>
    @foreach($categories as $cat)
      <a href="{{ route('menu', ['category' => $cat->slug, 'search' => $search]) }}" class="cat-pill {{ $selectedCategory === $cat->slug ? 'active' : '' }}">
        {{ $cat->name }}
      </a>
    @endforeach
  </div>

  <!-- Products Grid -->
  @if($products->count() > 0)
    <div class="menu-grid">
      @foreach($products as $item)
        <div class="menu-card">
          <a href="{{ route('menu.detail', $item->slug) }}">
            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="menu-card-img">
          </a>
          <div class="menu-card-body">
            <span style="font-size: 11.5px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">{{ $item->category->name ?? 'Menu' }}</span>
            <a href="{{ route('menu.detail', $item->slug) }}" class="menu-card-title">
              {{ $item->name }}
            </a>
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

    <div style="margin-top: 30px; display: flex; justify-content: center;">
      {{ $products->links() }}
    </div>
  @else
    <!-- Hasil Pencarian Tidak Ditemukan (Screen 62) -->
    <div class="card state-box">
      <img src="{{ asset('images/search-empty.svg') }}" alt="Pencarian Tidak Ditemukan" class="state-img">
      <h3 class="state-title">Tidak ada hasil ditemukan</h3>
      <p class="state-desc">Pencarian "{{ $search }}" tidak menemukan data menu yang sesuai.</p>
      <a href="{{ route('menu') }}" class="btn btn-primary">
        Lihat Semua Menu
      </a>
    </div>
  @endif
</div>
@endsection
