@extends('layouts.admin')

@section('title', 'Edit Produk — ' . $product->name)
@section('page_title', 'Edit Produk Menu')

@section('content')
<div class="card" style="max-width: 760px; margin: 0 auto; padding: 36px;">
  <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border-light); padding-bottom: 16px;">
    <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Edit Data Produk Menu</h3>
    <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Perbarui harga, stok, atau informasi menu {{ $product->name }}.</p>
  </div>

  <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div style="display: flex; gap: 20px;">
      <div class="form-group" style="flex: 1.5;">
        <label class="form-label" for="name">Nama Menu Produk <span class="required">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="form-control" required>
      </div>

      <div class="form-group" style="flex: 1;">
        <label class="form-label" for="sku">SKU / Kode Menu <span class="required">*</span></label>
        <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" class="form-control" required>
      </div>
    </div>

    <div class="form-group">
      <label class="form-label" for="category_id">Kategori Menu <span class="required">*</span></label>
      <select name="category_id" id="category_id" class="form-control form-select" required>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
    </div>

    <div style="display: flex; gap: 20px;">
      <div class="form-group" style="flex: 1;">
        <label class="form-label" for="buy_price">Harga Beli (Modal / HPP) <span class="required">*</span></label>
        <input type="number" name="buy_price" id="buy_price" value="{{ old('buy_price', (int)$product->buy_price) }}" class="form-control" required>
      </div>

      <div class="form-group" style="flex: 1;">
        <label class="form-label" for="sell_price">Harga Jual Menu <span class="required">*</span></label>
        <input type="number" name="sell_price" id="sell_price" value="{{ old('sell_price', (int)$product->sell_price) }}" class="form-control" required>
      </div>
    </div>

    <div style="display: flex; gap: 20px;">
      <div class="form-group" style="flex: 1;">
        <label class="form-label" for="stock">Stok Saat Ini <span class="required">*</span></label>
        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" class="form-control" min="0" required>
        <p style="font-size: 11.5px; color: var(--text-muted); margin-top: 4px;">Perubahan stok manual akan otomatis dicatat pada riwayat mutasi.</p>
      </div>

      <div class="form-group" style="flex: 1;">
        <label class="form-label" for="min_stock">Batas Stok Minimum <span class="required">*</span></label>
        <input type="number" name="min_stock" id="min_stock" value="{{ old('min_stock', $product->min_stock) }}" class="form-control" min="0" required>
      </div>
    </div>

    <div class="form-group">
      <label class="form-label" for="image">Ganti Foto Menu</label>
      <div style="display: flex; gap: 16px; align-items: center; margin-bottom: 8px;">
        <img src="{{ $product->image_url }}" alt="Preview" style="width: 50px; height: 50px; border-radius: var(--radius-md); object-fit: cover;">
        <input type="file" name="image" id="image" class="form-control" accept="image/*">
      </div>
    </div>

    <div class="form-group">
      <label class="form-label" for="description">Deskripsi Menu</label>
      <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
    </div>

    <div style="display: flex; gap: 24px; margin-bottom: 24px;">
      <label style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 600; cursor: pointer;">
        <input type="checkbox" name="is_available" value="1" {{ old('is_available', $product->is_available) ? 'checked' : '' }}>
        <span>Tersedia untuk dipesan</span>
      </label>

      <label style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 600; cursor: pointer;">
        <input type="checkbox" name="is_favorite" value="1" {{ old('is_favorite', $product->is_favorite) ? 'checked' : '' }}>
        <span>Tampilkan di Menu Favorit Beranda</span>
      </label>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center;">
      <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        Batal
      </a>
      <button type="submit" class="btn btn-primary btn-lg">
        Perbarui Produk
      </button>
    </div>
  </form>
</div>
@endsection
