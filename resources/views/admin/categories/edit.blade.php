@extends('layouts.admin')

@section('title', 'Edit Kategori — ' . $category->name)
@section('page_title', 'Edit Kategori')

@section('content')
<div class="card" style="max-width: 580px; margin: 0 auto; padding: 32px;">
  <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border-light); padding-bottom: 16px;">
    <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Edit Kategori</h3>
    <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Perbarui kategori {{ $category->name }}.</p>
  </div>

  <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
      <label class="form-label" for="name">Nama Kategori <span class="required">*</span></label>
      <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="form-control" required>
    </div>

    <div class="form-group">
      <label class="form-label" for="description">Deskripsi Singkat</label>
      <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
    </div>

    <div class="form-group">
      <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
        <span>Tampilkan di katalog publik customer</span>
      </label>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px;">
      <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
        Batal
      </a>
      <button type="submit" class="btn btn-primary btn-lg">
        Perbarui Kategori
      </button>
    </div>
  </form>
</div>
@endsection
