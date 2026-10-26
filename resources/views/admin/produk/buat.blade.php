@extends('layouts.admin')

@section('title', 'Tambah Produk Menu — Warmindo')
@section('page_title', 'Tambah Produk Baru')

@section('content')
<div class="card" style="max-width: 760px; margin: 0 auto; padding: 36px;">
  <!-- Header (Screen 16) -->
  <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border-light); padding-bottom: 16px;">
    <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Form Tambah Produk Menu</h3>
    <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Lengkapi informasi detail menu, harga, dan manajemen batas stok minimum.</p>
  </div>

  @if($errors->any())
    <div style="background-color: #FFE3E3; border-left: 4px solid #FA5252; color: #E03131; padding: 12px 18px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 24px;">
      Mohon periksa kembali form. Terdapat input yang belum sesuai.
    </div>
  @endif

  <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div style="display: flex; gap: 20px;">
      <div class="form-group" style="flex: 1.5;">
        <label class="form-label" for="name">Nama Menu Produk <span class="required">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Contoh: Indomie Goreng Spesial" required autofocus>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="form-group" style="flex: 1;">
        <label class="form-label" for="sku">SKU / Kode Menu <span class="required">*</span></label>
        <input type="text" name="sku" id="sku" value="{{ old('sku', 'WMW-' . rand(100, 999)) }}" class="form-control {{ $errors->has('sku') ? 'is-invalid' : '' }}" placeholder="Contoh: WMW-015" required>
        @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="form-group">
      <label class="form-label" for="category_id">Kategori Menu <span class="required">*</span></label>
      <select name="category_id" id="category_id" class="form-control form-select {{ $errors->has('category_id') ? 'is-invalid' : '' }}" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
      @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div style="display: flex; gap: 20px;">
      <div class="form-group" style="flex: 1;">
        <label class="form-label" for="buy_price">Harga Beli (Modal / HPP) <span class="required">*</span></label>
        <input type="number" name="buy_price" id="buy_price" value="{{ old('buy_price', 8000) }}" class="form-control {{ $errors->has('buy_price') ? 'is-invalid' : '' }}" placeholder="Rp 0" required>
        @error('buy_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="form-group" style="flex: 1;">
        <label class="form-label" for="sell_price">Harga Jual Menu <span class="required">*</span></label>
        <input type="number" name="sell_price" id="sell_price" value="{{ old('sell_price', 12000) }}" class="form-control {{ $errors->has('sell_price') ? 'is-invalid' : '' }}" placeholder="Rp 12.000" required>
        @error('sell_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>

    <div style="display: flex; gap: 20px;">
      <div class="form-group" style="flex: 1;">
        <label class="form-label" for="stock">Stok Awal <span class="required">*</span></label>
        <input type="number" name="stock" id="stock" value="{{ old('stock', 20) }}" class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}" min="0" required>
        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="form-group" style="flex: 1;">
        <label class="form-label" for="min_stock">Batas Stok Minimum <span class="required">*</span></label>
        <input type="number" name="min_stock" id="min_stock" value="{{ old('min_stock', 5) }}" class="form-control {{ $errors->has('min_stock') ? 'is-invalid' : '' }}" min="0" required>
        <p style="font-size: 11.5px; color: var(--text-muted); margin-top: 4px;">Sistem akan memberi peringatan jika stok mencapai nilai ini.</p>
        @error('min_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="form-group">
      <label class="form-label" for="image">Foto Produk Menu</label>
      <input type="file" name="image" id="image" class="form-control" accept="image/*">
      <p style="font-size: 11.5px; color: var(--text-muted); margin-top: 4px;">Format: PNG, JPG, JPEG (Maks. 2MB). Jika dikosongkan akan memakai foto ilustrasi default.</p>
    </div>

    <div class="form-group">
      <label class="form-label" for="description">Deskripsi Menu</label>
      <textarea name="description" id="description" class="form-control" rows="3" placeholder="Tuliskan bumbu, racikan, atau bahan pelengkap sajian ini...">{{ old('description') }}</textarea>
    </div>

    <div style="display: flex; gap: 24px; margin-bottom: 24px;">
      <label style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 600; cursor: pointer;">
        <input type="checkbox" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}>
        <span>Tersedia untuk dipesan</span>
      </label>

      <label style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 600; cursor: pointer;">
        <input type="checkbox" name="is_favorite" value="1" {{ old('is_favorite') ? 'checked' : '' }}>
        <span>Tampilkan di Menu Favorit Beranda</span>
      </label>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center;">
      <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        Batal
      </a>
      <button type="submit" class="btn btn-primary btn-lg">
        Simpan Produk Menu
      </button>
    </div>
  </form>
</div>
@endsection
