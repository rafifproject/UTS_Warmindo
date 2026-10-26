@extends('layouts.admin')

@section('title', 'Tambah Pengguna Baru — Warmindo')
@section('page_title', 'Tambah Pengguna')

@section('content')
<div class="card" style="max-width: 640px; margin: 0 auto; padding: 32px;">
  <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border-light); padding-bottom: 16px;">
    <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Form Tambah Pengguna</h3>
    <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Buat akun untuk Owner baru atau Staff Kasir / Petugas Dapur.</p>
  </div>

  <form action="{{ route('admin.users.store') }}" method="POST">
    @csrf

    <div class="form-group">
      <label class="form-label" for="name">Nama Lengkap <span class="required">*</span></label>
      <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Contoh: Budi Santoso" required>
      @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
      <label class="form-label" for="username">Username <span class="required">*</span></label>
      <input type="text" name="username" id="username" value="{{ old('username') }}" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" placeholder="Contoh: budi" required>
      @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
      <label class="form-label" for="email">Alamat Email <span class="required">*</span></label>
      <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Contoh: budi@warmindo.id" required>
      @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
      <label class="form-label" for="phone">Nomor Telepon / WhatsApp</label>
      <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control" placeholder="Contoh: 0812-3456-7890">
    </div>

    <div class="form-group">
      <label class="form-label" for="password">Password <span class="required">*</span></label>
      <input type="password" name="password" id="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Minimal 6 karakter" required>
      @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div style="display: flex; gap: 20px;">
      <div class="form-group" style="flex: 1;">
        <label class="form-label" for="role">Role Akses <span class="required">*</span></label>
        <select name="role" id="role" class="form-control form-select" required>
          <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff (Kasir & Dapur)</option>
          <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Owner (Akses Penuh)</option>
        </select>
      </div>

      <div class="form-group" style="flex: 1;">
        <label class="form-label" for="status">Status Akun <span class="required">*</span></label>
        <select name="status" id="status" class="form-control form-select" required>
          <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
          <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
      </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px;">
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        Batal
      </a>
      <button type="submit" class="btn btn-primary btn-lg">
        Simpan Pengguna
      </button>
    </div>
  </form>
</div>
@endsection
