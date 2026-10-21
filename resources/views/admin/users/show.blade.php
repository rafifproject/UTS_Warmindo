@extends('layouts.admin')

@section('title', 'Detail Pengguna — ' . $user->name)
@section('page_title', 'Detail Pengguna')

@section('content')
<div class="card" style="max-width: 680px; margin: 0 auto; padding: 32px;">
  <div style="display: flex; align-items: center; gap: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 24px; margin-bottom: 24px;">
    <div style="width: 64px; height: 64px; border-radius: 50%; background-color: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800;">
      {{ strtoupper(substr($user->name, 0, 2)) }}
    </div>
    <div>
      <h2 style="font-size: 22px; font-weight: 800; color: var(--text-dark); margin: 0 0 4px;">{{ $user->name }}</h2>
      <p style="font-size: 13.5px; color: var(--text-muted); margin: 0;">{{ $user->email }}</p>
    </div>
    <div style="margin-left: auto;">
      @if($user->status === 'active')
        <span class="badge badge-green"><span class="badge-dot"></span> Aktif</span>
      @else
        <span class="badge badge-red"><span class="badge-dot"></span> Nonaktif</span>
      @endif
    </div>
  </div>

  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px; font-size: 14px;">
    <div>
      <span style="color: var(--text-muted); font-size: 12.5px; display: block; margin-bottom: 4px;">Username:</span>
      <span style="font-weight: 700; color: var(--text-dark);">{{ $user->username ?? '-' }}</span>
    </div>
    <div>
      <span style="color: var(--text-muted); font-size: 12.5px; display: block; margin-bottom: 4px;">Role Akses:</span>
      <span style="font-weight: 700; color: var(--primary);">{{ $user->role === 'admin' ? 'Admin / Owner' : 'Staff Kasir / Dapur' }}</span>
    </div>
    <div>
      <span style="color: var(--text-muted); font-size: 12.5px; display: block; margin-bottom: 4px;">Nomor Telepon:</span>
      <span style="font-weight: 700; color: var(--text-dark);">{{ $user->phone ?? '-' }}</span>
    </div>
    <div>
      <span style="color: var(--text-muted); font-size: 12.5px; display: block; margin-bottom: 4px;">Terdaftar Sejak:</span>
      <span style="font-weight: 700; color: var(--text-dark);">{{ $user->created_at->format('d M Y') }}</span>
    </div>
  </div>

  <div style="display: flex; justify-content: space-between;">
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
      &larr; Kembali
    </a>
    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
      Edit Pengguna
    </a>
  </div>
</div>
@endsection
