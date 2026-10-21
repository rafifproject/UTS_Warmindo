@extends('layouts.public')

@section('title', $product->name . ' — Warmindo')

@section('content')
<div class="container" style="padding-top: 24px;">
  <!-- Breadcrumbs (Screen 52) -->
  <div style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
    <a href="{{ route('home') }}" style="color: var(--text-body);">Beranda</a>
    <span>&rsaquo;</span>
    <a href="{{ route('menu') }}" style="color: var(--text-body);">Menu</a>
    <span>&rsaquo;</span>
    <a href="{{ route('menu', ['category' => $product->category->slug ?? 'all']) }}" style="color: var(--text-body);">{{ $product->category->name ?? 'Kategori' }}</a>
    <span>&rsaquo;</span>
    <span style="color: var(--primary); font-weight: 600;">{{ $product->name }}</span>
  </div>

  <div class="card" style="padding: 36px; margin-bottom: 48px;">
    <div style="display: flex; flex-wrap: wrap; gap: 40px;">
      <!-- Product Gallery Preview -->
      <div style="flex: 1; min-width: 320px;">
        <div style="width: 100%; height: 380px; border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--border-light); background-color: #F8F9FA; margin-bottom: 16px;">
          <img src="{{ $item->image_url ?? $product->image_url }}" alt="{{ $product->name }}" id="mainProductImg" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <!-- Thumbnails -->
        <div style="display: flex; gap: 12px;">
          <div style="width: 80px; height: 80px; border-radius: var(--radius-md); overflow: hidden; border: 2px solid var(--primary); cursor: pointer;">
            <img src="{{ $product->image_url }}" alt="Thumbnail 1" style="width: 100%; height: 100%; object-fit: cover;">
          </div>
          <div style="width: 80px; height: 80px; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-light); opacity: 0.7; cursor: pointer;">
            <img src="{{ asset('images/menu/indomie-goreng.png') }}" alt="Thumbnail 2" style="width: 100%; height: 100%; object-fit: cover;">
          </div>
          <div style="width: 80px; height: 80px; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-light); opacity: 0.7; cursor: pointer;">
            <img src="{{ asset('images/menu/es-teh-manis.png') }}" alt="Thumbnail 3" style="width: 100%; height: 100%; object-fit: cover;">
          </div>
        </div>
      </div>

      <!-- Product Details & Add to Cart -->
      <div style="flex: 1.2; min-width: 320px; display: flex; flex-direction: column;">
        <div style="margin-bottom: 8px;">
          <span class="badge badge-green">
            <span class="badge-dot"></span> Tersedia
          </span>
          <span style="font-size: 13px; color: var(--text-muted); margin-left: 10px;">SKU: {{ $product->sku }}</span>
        </div>

        <h1 style="font-size: 32px; font-weight: 800; color: var(--text-dark); margin-bottom: 10px;">{{ $product->name }}</h1>
        <div style="font-size: 26px; font-weight: 800; color: var(--primary); margin-bottom: 20px;">
          {{ $product->formatted_sell_price }}
        </div>

        <div style="border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light); padding: 18px 0; margin-bottom: 24px;">
          <h4 style="font-size: 14px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px;">Deskripsi Menu</h4>
          <p style="font-size: 14.5px; color: var(--text-body); line-height: 1.6;">
            {{ $product->description ?? 'Mie lezat dengan racikan bumbu khas Warmindo, pas disantap hangat kapan saja.' }}
          </p>
        </div>

        <!-- Add to Cart Form with Stepper -->
        <form action="{{ route('cart.add', $product->id) }}" method="POST" style="margin-top: auto;">
          @csrf
          <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 24px;">
            <span style="font-size: 14px; font-weight: 600; color: var(--text-dark);">Jumlah:</span>
            <div style="display: inline-flex; align-items: center; border: 1.5px solid var(--border-default); border-radius: var(--radius-md); overflow: hidden; background: #FFFFFF;">
              <button type="button" onclick="decrementQty()" style="width: 40px; height: 40px; background: none; border: none; font-size: 18px; font-weight: 700; cursor: pointer; color: var(--text-body);">-</button>
              <input type="number" name="quantity" id="detailQty" value="1" min="1" max="{{ $product->stock }}" style="width: 50px; text-align: center; border: none; font-size: 16px; font-weight: 700; color: var(--text-dark); outline: none;">
              <button type="button" onclick="incrementQty()" style="width: 40px; height: 40px; background: none; border: none; font-size: 18px; font-weight: 700; cursor: pointer; color: var(--text-body);">+</button>
            </div>
            <span style="font-size: 13px; color: var(--text-muted);">(Tersedia {{ $product->stock }} porsi)</span>
          </div>

          <button type="submit" class="btn btn-primary btn-lg btn-block" style="display: flex; gap: 10px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
            Tambah ke Keranjang
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Related Menus -->
  @if($relatedProducts->count() > 0)
    <h3 style="font-size: 20px; font-weight: 800; color: var(--text-dark); margin-bottom: 20px;">Menu Terkait</h3>
    <div class="menu-grid">
      @foreach($relatedProducts as $rel)
        <div class="menu-card">
          <a href="{{ route('menu.detail', $rel->slug) }}">
            <img src="{{ $rel->image_url }}" alt="{{ $rel->name }}" class="menu-card-img">
          </a>
          <div class="menu-card-body">
            <a href="{{ route('menu.detail', $rel->slug) }}" class="menu-card-title">
              {{ $rel->name }}
            </a>
            <div class="menu-card-price">{{ $rel->formatted_sell_price }}</div>
            <div class="menu-card-footer">
              <form action="{{ route('cart.add', $rel->id) }}" method="POST">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn btn-primary btn-block btn-sm">+ Tambah</button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>

<script>
  function incrementQty() {
    const input = document.getElementById('detailQty');
    const max = parseInt(input.getAttribute('max') || 99);
    let val = parseInt(input.value || 1);
    if (val < max) input.value = val + 1;
  }
  function decrementQty() {
    const input = document.getElementById('detailQty');
    let val = parseInt(input.value || 1);
    if (val > 1) input.value = val - 1;
  }
</script>
@endsection
