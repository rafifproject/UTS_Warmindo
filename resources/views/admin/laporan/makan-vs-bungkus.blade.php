@extends('layouts.admin')

@section('title', 'Laporan Dine In vs Take Away — Warmindo')
@section('page_title', 'Laporan Dine In vs Take Away')

@section('content')
@include('admin.laporan._navigasi')

<div class="card" style="margin-bottom: 24px; padding: 20px 24px;">
  <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px;">
    <!-- Period Filters -->
    <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
      <span style="font-size: 13px; font-weight: 700; color: var(--text-dark);">Periode:</span>
      <a href="{{ route('admin.reports.dine-in-vs-take-away', ['period' => 'today']) }}" class="btn btn-sm {{ $period === 'today' ? 'btn-primary' : 'btn-secondary' }}">Hari Ini</a>
      <a href="{{ route('admin.reports.dine-in-vs-take-away', ['period' => 'week']) }}" class="btn btn-sm {{ $period === 'week' ? 'btn-primary' : 'btn-secondary' }}">Minggu Ini</a>
      <a href="{{ route('admin.reports.dine-in-vs-take-away', ['period' => 'month']) }}" class="btn btn-sm {{ $period === 'month' ? 'btn-primary' : 'btn-secondary' }}">Bulan Ini</a>
    </div>

    <!-- Toggle & Actions -->
    <div style="display: flex; gap: 12px; align-items: center;">
      <div class="report-toggle-group">
        <button type="button" id="btnShowChart" class="report-toggle-btn active">📊 Mode Chart</button>
        <button type="button" id="btnShowTable" class="report-toggle-btn">📋 Mode Data Mentah</button>
      </div>

      <a href="{{ route('admin.ai.analyze', ['prompt' => 'Bandingkan volume pesanan Dine In versus Take Away di Warmindo dan berikan rekomendasi penataan tempat duduk atau kemasan bungkus']) }}" class="btn btn-primary btn-sm" style="background-color: #6366F1; border-color: #6366F1;">
        ✨ Analisis dengan AI
      </a>
    </div>
  </div>
</div>

<!-- Comparison Metrics Cards -->
<div class="stat-grid" style="margin-bottom: 24px;">
  <div class="stat-card">
    <div>
      <div class="stat-label">Dine In (Makan di Tempat)</div>
      <div class="stat-value" style="color: var(--primary);">{{ $dineInCount }} Pesanan</div>
      <span style="font-size: 12px; color: var(--text-muted);">Omzet: Rp {{ number_format($dineInRevenue, 0, ',', '.') }}</span>
    </div>
    <div class="stat-icon orange">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10h18M3 14h18M5 10v8M19 10v8M10 6h4M12 6v4"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Take Away (Bungkus)</div>
      <div class="stat-value" style="color: #1C7ED6;">{{ $takeAwayCount }} Pesanan</div>
      <span style="font-size: 12px; color: var(--text-muted);">Omzet: Rp {{ number_format($takeAwayRevenue, 0, ',', '.') }}</span>
    </div>
    <div class="stat-icon blue">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
    </div>
  </div>
</div>

<!-- 1. MODE CHART -->
<div id="reportChartSection" class="card" style="margin-bottom: 24px;">
  <div class="card-header">
    <div>
      <h3 class="card-title">Proporsi Volume Layanan</h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Perbandingan persentase order makan di tempat vs bungkus</p>
    </div>
  </div>
  <div style="height: 300px; position: relative; display: flex; align-items: center; justify-content: center;">
    <canvas id="dineVsTakeChart"></canvas>
  </div>
</div>

<!-- 2. MODE DATA MENTAH -->
<div id="reportTableSection" class="card" style="padding: 0; overflow: hidden; display: none;">
  <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-light);">
    <h3 class="card-title">Tabel Data Mentah Layanan</h3>
  </div>

  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px;">No. Pesanan</th>
          <th>Jenis Layanan</th>
          <th>Nomor Meja</th>
          <th>Tanggal &amp; Waktu</th>
          <th style="text-align: right; padding-right: 24px;">Total Tagihan</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rawRecords as $rec)
          <tr>
            <td style="padding-left: 24px; font-weight: 700; color: var(--primary);">#{{ $rec->sale_number }}</td>
            <td>
              @if($rec->order_type === 'dine_in')
                <span class="badge badge-blue">Dine In</span>
              @else
                <span class="badge badge-orange">Take Away</span>
              @endif
            </td>
            <td style="font-weight: 600;">{{ $rec->table_number ? 'Meja ' . $rec->table_number : '-' }}</td>
            <td style="font-size: 13px; color: var(--text-muted);">{{ $rec->paid_at ? $rec->paid_at->format('d/m/Y H:i') : '-' }}</td>
            <td style="text-align: right; font-weight: 800; padding-right: 24px;">{{ $rec->formatted_total }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align: center; padding: 36px; color: var(--text-muted);">
              Tidak ada data.
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
  const ctx = document.getElementById('dineVsTakeChart').getContext('2d');
  new Chart(ctx, {
    type: 'pie',
    data: {
      labels: {!! json_encode($chartLabels) !!},
      datasets: [{
        data: {!! json_encode($chartValues) !!},
        backgroundColor: ['#F06524', '#1C7ED6'],
        borderWidth: 2,
        borderColor: '#FFFFFF',
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom' }
      }
    }
  });
</script>
@endpush
