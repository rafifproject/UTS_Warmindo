@extends('layouts.admin')

@section('title', 'Laporan Penjualan — Warmindo')
@section('page_title', 'Laporan Penjualan')

@section('content')
@include('admin.reports._nav')

<!-- Filter & Toggle Toolbar (Screen H) -->
<div class="card" style="margin-bottom: 24px; padding: 20px 24px;">
  <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px;">
    <!-- Period Filters -->
    <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
      <span style="font-size: 13px; font-weight: 700; color: var(--text-dark);">Periode:</span>
      <a href="{{ route('admin.reports.sales', ['period' => 'today']) }}" class="btn btn-sm {{ $period === 'today' ? 'btn-primary' : 'btn-secondary' }}">Hari Ini</a>
      <a href="{{ route('admin.reports.sales', ['period' => 'week']) }}" class="btn btn-sm {{ $period === 'week' ? 'btn-primary' : 'btn-secondary' }}">Minggu Ini</a>
      <a href="{{ route('admin.reports.sales', ['period' => 'month']) }}" class="btn btn-sm {{ $period === 'month' ? 'btn-primary' : 'btn-secondary' }}">Bulan Ini</a>

      <!-- Custom Date Picker -->
      <form action="{{ route('admin.reports.sales') }}" method="GET" style="display: inline-flex; gap: 6px; align-items: center; margin-left: 10px;">
        <input type="hidden" name="period" value="custom">
        <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="form-control" style="width: 140px; padding: 6px 10px; font-size: 13px;">
        <span style="color: var(--text-muted);">-</span>
        <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="form-control" style="width: 140px; padding: 6px 10px; font-size: 13px;">
        <button type="submit" class="btn btn-primary btn-sm">Terapkan</button>
      </form>
    </div>

    <!-- Toggle Mode Chart vs Data Mentah & Actions (Screen H) -->
    <div style="display: flex; gap: 12px; align-items: center;">
      <div class="report-toggle-group">
        <button type="button" id="btnShowChart" class="report-toggle-btn active">📊 Mode Chart</button>
        <button type="button" id="btnShowTable" class="report-toggle-btn">📋 Mode Data Mentah</button>
      </div>

      <a href="{{ route('admin.reports.export', ['type' => 'sales', 'period' => $period, 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-secondary btn-sm" title="Download CSV">
        Ekspor CSV
      </a>

      <a href="{{ route('admin.ai.analyze', ['prompt' => 'Analisis tren penjualan dan omzet Warmindo untuk periode ' . $startDate->format('d M Y') . ' sampai ' . $endDate->format('d M Y')]) }}" class="btn btn-primary btn-sm" style="background-color: #6366F1; border-color: #6366F1;">
        ✨ Analisis dengan AI
      </a>
    </div>
  </div>
</div>

<!-- Summary Metrics Bar -->
<div class="stat-grid" style="margin-bottom: 24px;">
  <div class="stat-card">
    <div>
      <div class="stat-label">Total Omzet Penjualan</div>
      <div class="stat-value" style="color: var(--primary);">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="stat-icon orange">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Jumlah Transaksi Lunas</div>
      <div class="stat-value">{{ $totalOrders }}</div>
    </div>
    <div class="stat-icon green">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Rata-rata Transaksi (AOV)</div>
      <div class="stat-value" style="color: #1C7ED6;">
        Rp {{ number_format($totalOrders > 0 ? round($totalRevenue / $totalOrders) : 0, 0, ',', '.') }}
      </div>
    </div>
    <div class="stat-icon blue">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
    </div>
  </div>
</div>

<!-- 1. MODE CHART (Grafik) -->
<div id="reportChartSection" class="card" style="margin-bottom: 24px;">
  <div class="card-header">
    <div>
      <h3 class="card-title">Grafik Tren Penjualan Harian</h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Omzet harian selama periode terpilih</p>
    </div>
  </div>
  <div style="height: 340px; position: relative;">
    <canvas id="salesReportChart"></canvas>
  </div>
</div>

<!-- 2. MODE DATA MENTAH (Tabel) -->
<div id="reportTableSection" class="card" style="padding: 0; overflow: hidden; display: none;">
  <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-light);">
    <h3 class="card-title">Tabel Data Mentah Penjualan</h3>
    <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Rincian setiap transaksi lunas pada periode ini</p>
  </div>

  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px;">Tanggal &amp; Jam</th>
          <th>No. Transaksi</th>
          <th>Layanan</th>
          <th>Menu Dipesan</th>
          <th style="text-align: right;">Total Nilai</th>
          <th>Metode</th>
          <th>Status</th>
          <th style="text-align: right; padding-right: 24px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rawRecords as $rec)
          <tr>
            <td style="padding-left: 24px; font-size: 13px;">{{ $rec->paid_at ? $rec->paid_at->format('d/m/Y H:i') : '-' }}</td>
            <td style="font-weight: 700; color: var(--primary);">#{{ $rec->sale_number }}</td>
            <td>{{ $rec->order_type === 'dine_in' ? 'Meja ' . $rec->table_number : 'Take Away' }}</td>
            <td style="max-width: 240px; font-size: 13px;">{{ $rec->items_summary }}</td>
            <td style="text-align: right; font-weight: 700;">{{ $rec->formatted_total }}</td>
            <td><span style="font-size: 11.5px; text-transform: uppercase;">{{ $rec->payment_method ?? 'TUNAI' }}</span></td>
            <td><span class="badge badge-green">Lunas</span></td>
            <td style="text-align: right; padding-right: 24px;">
              <a href="{{ route('admin.sales.show', $rec->id) }}" class="btn btn-secondary btn-sm">Lihat</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" style="text-align: center; padding: 36px; color: var(--text-muted);">
              Tidak ada data transaksi penjualan pada periode ini.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($rawRecords->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
      <span style="font-size: 13px; color: var(--text-muted);">Menampilkan {{ $rawRecords->firstItem() }} - {{ $rawRecords->lastItem() }} dari {{ $rawRecords->total() }} transaksi</span>
      {{ $rawRecords->links() }}
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
  const ctx = document.getElementById('salesReportChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: {!! json_encode($chartLabels) !!},
      datasets: [{
        label: 'Omzet Harian (Rp)',
        data: {!! json_encode($chartValues) !!},
        borderColor: '#F06524',
        backgroundColor: 'rgba(240, 101, 36, 0.1)',
        borderWidth: 3,
        fill: true,
        tension: 0.3,
        pointBackgroundColor: '#FFFFFF',
        pointBorderColor: '#F06524',
        pointBorderWidth: 2,
        pointRadius: 5,
        pointHoverRadius: 7,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(ctx) {
              return 'Rp ' + ctx.raw.toLocaleString('id-ID');
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(v) { return 'Rp ' + (v / 1000) + 'k'; }
          }
        }
      }
    }
  });
</script>
@endpush
