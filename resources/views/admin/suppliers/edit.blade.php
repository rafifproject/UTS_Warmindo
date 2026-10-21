@extends('layouts.admin')

@section('title', 'Edit Supplier — ' . $supplier->name)
@section('page_title', 'Edit Data Supplier')

@section('content')
<div class="card" style="max-width: 620px; margin: 0 auto; padding: 32px;">
  <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border-light); padding-bottom: 16px;">
    <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Edit Data Supplier</h3>
    <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Perbarui informasi pemasok {{ $supplier->name }}.</p>
  </div>

  <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
      <label class="form-label" for="name">Nama Perusahaan / Supplier <span class="required">*</span></label>
      <input type="text" name="name" id="name" value="{{ old('name', $supplier->name) }}" class="form-control" required>
    </div>

    <div class="form-group">
      <label class="form-label" for="contact_person">Nama Kontak (PIC)</label>
      <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}" class="form-control">
    </div>

    <div style="display: flex; gap: 20px;">
      <div class="form-group" style="flex: 1;">
        <label class="form-label" for="phone">Nomor Telepon</label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', $supplier->phone) }}" class="form-control">
      </div>

      <div class="form-group" style="flex: 1;">
        <label class="form-label" for="email">Alamat Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $supplier->email) }}" class="form-control">
      </div>
    </div>

    <div class="form-group">
      <label class="form-label" for="address">Alamat Kantor / Gudang</label>
      <textarea name="address" id="address" class="form-control" rows="3">{{ old('address', $supplier->address) }}</textarea>
    </div>

    <div class="form-group">
      <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}>
        <span>Supplier Aktif</span>
      </label>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px;">
      <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">
        Batal
      </a>
      <button type="submit" class="btn btn-primary btn-lg">
        Perbarui Supplier
      </button>
    </div>
  </form>
</div>
@endsection
