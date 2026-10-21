@extends('layouts.admin')

@section('title', 'Laporan Menu Terlaris — Warmindo')
@section('page_title', 'Laporan Menu Terlaris')

@section('content')
@include('admin.reports._nav')

<!-- Filter & Toggle Toolbar (Screen H) -->
<div class="card" style="margin-bottom: 24px; padding: 20px 24px;">
  <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px;">
    <!-- Period Filters -->
    <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
      <span style="font-size: 13px; font-weight: 700; color: var(--text-dark);">Periode:</span>
      <a href="{{ route('admin.reports.top-menus', ['period' => 'today']) }}" class="btn btn-sm {{ $period === 'today' ? 'btn-primary' : 'btn-secondary' }}">Hari Ini</a>
      <a href="{{ route('admin.reports.top-menus', ['period' => 'week']) }}" class="btn btn-sm {{ $period === 'week' ? 'btn-primary' : 'btn-secondary' }}">Minggu Ini</a>
      <a href="{{ route('admin.reports.top-menus', ['period' => 'month']) }}" class="btn btn-sm {{ $period === 'month' ? 'btn-primary' : 'btn-secondary' }}">Bulan Ini</a>
    </div>

    <!-- Toggle Mode Chart vs Data Mentah -->
    <div style="display: flex; gap: 12px; align-items: center;">
      <div class="report-toggle-group">
        <button type="button" id="btnShowChart" class="report-toggle-btn active">📊 Mode Chart</button>
        <button type="button" id="btnShowTable" class="report-toggle-btn">📋 Mode Data Mentah</button>
      </div>

      <a href="{{ route('admin.ai.analyze', ['prompt' => 'Analisis menu paling laris dan menu yang kurang laku untuk evaluasi menu Warmindo']) }}" class="btn btn-primary btn-sm" style="background-color: #6366F1; border-color: #6366F1;">
        ✨ Analisis dengan AI
      </a>
    </div>
  </div>
</div>

<!-- 1. MODE CHART: Bar Chart Menu Terjual -->
<div id="reportChartSection" class="card" style="margin-bottom: 24px;">
  <div class="card-header">
    <div>
      <h3 class="card-title">Grafik Menu Paling Banyak Terjual (Porsi)</h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Peringkat volume menu laku selama periode terpilih</p>
    </div>
  </div>
  <div style="height: 340px; position: relative;">
    <canvas id="topMenuChart"></canvas>
  </div>
</div>

<!-- 2. MODE DATA MENTAH: Tabel Menu Terlaris -->
<div id="reportTableSection" class="card" style="padding: 0; overflow: hidden; display: none;">
  <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-light);">
    <h3 class="card-title">Tabel Data Mentah Menu Terlaris</h3>
  </div>

  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px; width: 60px;">Peringkat</th>
          <th>Nama Menu Produk</th>
          <th style="text-align: center;">Jumlah Porsi Terjual</th>
          <th style="text-align: right; padding-right: 24px;">Kontribusi Omzet</th>
        </tr>
      </thead>
      <tbody>
        @forelse($topItems as $idx => $it)
          <tr>
            <td style="padding-left: 24px; font-weight: 800; color: {{ $idx < 3 ? 'var(--primary)' : 'var(--text-muted)' }};">
              #{{ $idx + 1 }}
            </td>
            <td style="font-weight: 700; color: var(--text-dark); font-size: 14.5px;">
              {{ $it->product_name }}
              @if($idx === 0)
                <span class="badge badge-orange" style="margin-left: 8px;">👑 Terlaris</span>
              @endif
            </td>
            <td style="text-align: center; font-weight: 800; color: var(--text-dark); font-size: 15px;">
              {{ $it->total_qty }} porsi
            </td>
            <td style="text-align: right; font-weight: 800; color: #0CA678; padding-right: 24px;">
              Rp {{ number_format($it->total_revenue, 0, ',', '.') }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" style="text-align: center; padding: 36px; color: var(--text-muted);">
              Belum ada data penjualan menu pada periode ini.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const ctx = document.getElementById('topMenuChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: {!! json_encode($chartLabels) !!},
      datasets: [{
        label: 'Jumlah Terjual (Porsi)',
        data: {!! json_encode($chartValues) !!},
        backgroundColor: '#F06524',
        borderRadius: 8,
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false }
      },
      scales: {
        x: { beginAtZero: true }
      }
    }
  });
</script>
@endpush
