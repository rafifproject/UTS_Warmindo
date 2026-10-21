@extends('layouts.admin')

@section('title', 'Laporan Transaksi — Warmindo')
@section('page_title', 'Laporan Transaksi')

@section('content')
@include('admin.reports._nav')

<!-- Filter & Toggle Toolbar (Screen H) -->
<div class="card" style="margin-bottom: 24px; padding: 20px 24px;">
  <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px;">
    <!-- Period Filters -->
    <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
      <span style="font-size: 13px; font-weight: 700; color: var(--text-dark);">Periode:</span>
      <a href="{{ route('admin.reports.transactions', ['period' => 'today']) }}" class="btn btn-sm {{ $period === 'today' ? 'btn-primary' : 'btn-secondary' }}">Hari Ini</a>
      <a href="{{ route('admin.reports.transactions', ['period' => 'week']) }}" class="btn btn-sm {{ $period === 'week' ? 'btn-primary' : 'btn-secondary' }}">Minggu Ini</a>
      <a href="{{ route('admin.reports.transactions', ['period' => 'month']) }}" class="btn btn-sm {{ $period === 'month' ? 'btn-primary' : 'btn-secondary' }}">Bulan Ini</a>
    </div>

    <!-- Toggle Mode Chart vs Data Mentah & Actions -->
    <div style="display: flex; gap: 12px; align-items: center;">
      <div class="report-toggle-group">
        <button type="button" id="btnShowChart" class="report-toggle-btn active">📊 Mode Chart</button>
        <button type="button" id="btnShowTable" class="report-toggle-btn">📋 Mode Data Mentah</button>
      </div>

      <a href="{{ route('admin.ai.analyze', ['prompt' => 'Analisis rasio status transaksi dan jumlah order Warmindo pada periode ' . $startDate->format('d M') . ' sampai ' . $endDate->format('d M Y')]) }}" class="btn btn-primary btn-sm" style="background-color: #6366F1; border-color: #6366F1;">
        ✨ Analisis dengan AI
      </a>
    </div>
  </div>
</div>

<!-- 1. MODE CHART -->
<div id="reportChartSection" class="card" style="margin-bottom: 24px;">
  <div class="card-header">
    <div>
      <h3 class="card-title">Grafik Perbandingan Status Transaksi</h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Jumlah pesanan lunas, menunggu, dan dibatalkan</p>
    </div>
  </div>
  <div style="height: 320px; position: relative;">
    <canvas id="trxReportChart"></canvas>
  </div>
</div>

<!-- 2. MODE DATA MENTAH -->
<div id="reportTableSection" class="card" style="padding: 0; overflow: hidden; display: none;">
  <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-light);">
    <h3 class="card-title">Tabel Rincian Setiap Transaksi</h3>
  </div>

  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px;">No. Transaksi</th>
          <th>Tanggal &amp; Waktu</th>
          <th>Layanan</th>
          <th>Total Nilai</th>
          <th>Status Bayar</th>
          <th>Status Masakan</th>
          <th style="text-align: right; padding-right: 24px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rawRecords as $rec)
          <tr>
            <td style="padding-left: 24px; font-weight: 700; color: var(--primary);">#{{ $rec->sale_number }}</td>
            <td style="font-size: 13px;">{{ $rec->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $rec->order_type === 'dine_in' ? 'Meja ' . $rec->table_number : 'Take Away' }}</td>
            <td style="font-weight: 700;">{{ $rec->formatted_total }}</td>
            <td>
              @if($rec->payment_status === 'paid')
                <span class="badge badge-green">Lunas</span>
              @elseif($rec->payment_status === 'pending')
                <span class="badge badge-orange">Menunggu</span>
              @else
                <span class="badge badge-red">Dibatalkan</span>
              @endif
            </td>
            <td>
              <span class="badge badge-blue">{{ strtoupper(str_replace('_', ' ', $rec->order_status)) }}</span>
            </td>
            <td style="text-align: right; padding-right: 24px;">
              <a href="{{ route('admin.sales.show', $rec->id) }}" class="btn btn-secondary btn-sm">Lihat</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align: center; padding: 36px; color: var(--text-muted);">
              Tidak ada data transaksi.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($rawRecords->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
      <span style="font-size: 13px; color: var(--text-muted);">Total {{ $rawRecords->total() }} transaksi</span>
      {{ $rawRecords->links() }}
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
  const ctx = document.getElementById('trxReportChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: {!! json_encode($chartLabels) !!},
      datasets: [{
        label: 'Jumlah Transaksi',
        data: {!! json_encode($chartValues) !!},
        backgroundColor: ['#12B886', '#F59F00', '#FA5252'],
        borderRadius: 8,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false }
      },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>
@endpush
