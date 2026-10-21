@extends('layouts.admin')

@section('title', 'Supplier — Warmindo')
@section('page_title', 'Master Pemasok (Supplier)')

@section('content')
<div class="card" style="padding: 0; overflow: hidden;">
  <!-- Header Bar (Screen 22) -->
  <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-light); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
    <div>
      <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark);">Daftar Supplier</h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Kelola data pemasok mie, bumbu, minuman, dan bahan baku Warmindo</p>
    </div>

    <div style="display: flex; gap: 10px;">
      <form action="{{ route('admin.suppliers.index') }}" method="GET" style="display: flex; gap: 8px;">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama / kontak / telepon..." style="width: 220px;">
        <button type="submit" class="btn btn-primary btn-sm">Cari</button>
      </form>
      <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
        + Tambah Supplier
      </a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px; width: 50px;">No.</th>
          <th>Nama Supplier</th>
          <th>Kontak PIC</th>
          <th>Telepon</th>
          <th style="text-align: center;">Total Pembelian</th>
          <th>Status</th>
          <th style="text-align: right; padding-right: 24px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($suppliers as $idx => $sup)
          <tr>
            <td style="padding-left: 24px; color: var(--text-muted);">{{ $suppliers->firstItem() + $idx }}</td>
            <td>
              <div style="font-weight: 700; color: var(--text-dark); font-size: 14.5px;">{{ $sup->name }}</div>
              <div style="font-size: 12px; color: var(--text-muted);">{{ $sup->address ? Str::limit($sup->address, 35) : '-' }}</div>
            </td>
            <td>{{ $sup->contact_person ?? '-' }}</td>
            <td>{{ $sup->phone ?? '-' }}</td>
            <td style="text-align: center; font-weight: 700;">
              <span class="badge badge-blue">{{ $sup->purchases_count }}x Transaksi</span>
            </td>
            <td>
              @if($sup->is_active)
                <span class="badge badge-green"><span class="badge-dot"></span> Aktif</span>
              @else
                <span class="badge badge-red"><span class="badge-dot"></span> Nonaktif</span>
              @endif
            </td>
            <td style="text-align: right; padding-right: 24px;">
              <div style="display: inline-flex; gap: 6px;">
                <a href="{{ route('admin.suppliers.show', $sup->id) }}" class="btn btn-secondary btn-sm">Lihat</a>
                <a href="{{ route('admin.suppliers.edit', $sup->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                <form action="{{ route('admin.suppliers.destroy', $sup->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data supplier ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted);">
              Belum ada data supplier yang tersimpan.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($suppliers->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
      <span style="font-size: 13px; color: var(--text-muted);">Menampilkan {{ $suppliers->firstItem() }} - {{ $suppliers->lastItem() }} dari {{ $suppliers->total() }} supplier</span>
      {{ $suppliers->links() }}
    </div>
  @endif
</div>
@endsection
