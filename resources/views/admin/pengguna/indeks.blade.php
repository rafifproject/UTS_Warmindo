@extends('layouts.admin')

@section('title', 'Manajemen Pengguna & Role — Warmindo')
@section('page_title', 'Manajemen Pengguna & Role')

@section('content')
<div class="card" style="padding: 0; overflow: hidden;">
  <!-- Header Bar (Screen 6) -->
  <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-light); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
    <div>
      <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark);">Daftar Pengguna Sistem</h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Kelola hak akses Admin Owner dan Staff Kasir/Dapur</p>
    </div>

    <div style="display: flex; gap: 10px;">
      <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: 8px;">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama / email / username..." style="width: 240px;">
        <button type="submit" class="btn btn-primary btn-sm">Cari</button>
      </form>
      <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        + Tambah Pengguna
      </a>
    </div>
  </div>

  <!-- Table (Screen 6) -->
  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px; width: 50px;">No.</th>
          <th>Nama Lengkap</th>
          <th>Username / Email</th>
          <th>Role Akses</th>
          <th>Status</th>
          <th style="text-align: right; padding-right: 24px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $idx => $u)
          <tr>
            <td style="padding-left: 24px; color: var(--text-muted);">{{ $users->firstItem() + $idx }}</td>
            <td>
              <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; border-radius: 50%; background-color: {{ $u->role === 'admin' ? 'var(--primary-light)' : 'var(--bg-muted)' }}; color: {{ $u->role === 'admin' ? 'var(--primary)' : 'var(--text-body)' }}; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                  {{ strtoupper(substr($u->name, 0, 2)) }}
                </div>
                <div>
                  <div style="font-weight: 700; color: var(--text-dark);">{{ $u->name }}</div>
                  <div style="font-size: 12px; color: var(--text-muted);">{{ $u->phone ?? 'Tanpa telepon' }}</div>
                </div>
              </div>
            </td>
            <td>
              <div style="font-weight: 600;">{{ $u->username ?? '-' }}</div>
              <div style="font-size: 12px; color: var(--text-muted);">{{ $u->email }}</div>
            </td>
            <td>
              @if($u->role === 'admin')
                <span class="badge badge-orange">Admin Owner</span>
              @else
                <span class="badge badge-blue">Staff Kasir / Dapur</span>
              @endif
            </td>
            <td>
              @if($u->status === 'active')
                <span class="badge badge-green"><span class="badge-dot"></span> Aktif</span>
              @else
                <span class="badge badge-red"><span class="badge-dot"></span> Nonaktif</span>
              @endif
            </td>
            <td style="text-align: right; padding-right: 24px;">
              <div style="display: inline-flex; gap: 8px;">
                <a href="{{ route('admin.users.show', $u->id) }}" class="btn btn-secondary btn-sm" title="Detail">
                  Lihat
                </a>
                <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-secondary btn-sm" title="Edit">
                  Edit
                </a>
                @if($u->id !== auth()->id())
                  <form action="{{ route('admin.users.toggle-status', $u->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status pengguna ini?');">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $u->status === 'active' ? 'btn-danger' : 'btn-success' }}">
                      {{ $u->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  @if($users->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
      <span style="font-size: 13px; color: var(--text-muted);">Total {{ $users->total() }} pengguna terdaftar</span>
      {{ $users->links() }}
    </div>
  @endif
</div>
@endsection
