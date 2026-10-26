@extends('layouts.admin')

@section('title', 'Edit Pengguna — ' . $user->name)
@section('page_title', 'Edit Pengguna')

@section('content')
<div class="card" style="max-width: 640px; margin: 0 auto; padding: 32px;">
  <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border-light); padding-bottom: 16px;">
    <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Edit Data Pengguna</h3>
    <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Perbarui informasi atau role untuk akun {{ $user->name }}.</p>
  </div>

  <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
      <label class="form-label" for="name">Nama Lengkap <span class="required">*</span></label>
      <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control" required>
    </div>

    <div class="form-group">
      <label class="form-label" for="username">Username <span class="required">*</span></label>
      <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="form-control" required>
    </div>

    <div class="form-group">
      <label class="form-label" for="email">Alamat Email <span class="required">*</span></label>
      <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control" required>
    </div>

    <div class="form-group">
      <label class="form-label" for="phone">Nomor Telepon</label>
      <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
    </div>

    <div class="form-group">
      <label class="form-label" for="password">Password Baru (opsional)</label>
      <input type="password" name="password" id="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
    </div>

    <div style="display: flex; gap: 20px;">
      <div class="form-group" style="flex: 1;">
        <label class="form-label" for="role">Role Akses <span class="required">*</span></label>
        <select name="role" id="role" class="form-control form-select" required>
          <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff (Kasir & Dapur)</option>
          <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin / Owner (Akses Penuh)</option>
        </select>
      </div>

      <div class="form-group" style="flex: 1;">
        <label class="form-label" for="status">Status Akun <span class="required">*</span></label>
        <select name="status" id="status" class="form-control form-select" required>
          <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Aktif</option>
          <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
      </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px;">
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        Batal
      </a>
      <button type="submit" class="btn btn-primary btn-lg">
        Perbarui Pengguna
      </button>
    </div>
  </form>
</div>
@endsection
