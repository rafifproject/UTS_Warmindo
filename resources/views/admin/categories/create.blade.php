@extends('layouts.admin')

@section('title', 'Tambah Kategori — Warmindo')
@section('page_title', 'Tambah Kategori Baru')

@section('content')
<div class="card" style="max-width: 580px; margin: 0 auto; padding: 32px;">
  <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border-light); padding-bottom: 16px;">
    <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Form Kategori</h3>
    <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Buat kategori baru untuk mengelompokkan menu.</p>
  </div>

  <form action="{{ route('admin.categories.store') }}" method="POST">
    @csrf

    <div class="form-group">
      <label class="form-label" for="name">Nama Kategori <span class="required">*</span></label>
      <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Contoh: Aneka Indomie" required autofocus>
      @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
      <label class="form-label" for="description">Deskripsi Singkat</label>
      <textarea name="description" id="description" class="form-control" rows="3" placeholder="Jelaskan jenis menu dalam kategori ini...">{{ old('description') }}</textarea>
    </div>

    <div class="form-group">
      <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
        <span>Tampilkan di katalog publik customer</span>
      </label>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px;">
      <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
        Batal
      </a>
      <button type="submit" class="btn btn-primary btn-lg">
        Simpan Kategori
      </button>
    </div>
  </form>
</div>
@endsection
