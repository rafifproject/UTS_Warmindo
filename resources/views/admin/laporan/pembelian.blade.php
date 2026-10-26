@extends('layouts.admin')

@section('title', 'Laporan Pembelian & Pasokan — Warmindo')
@section('page_title', 'Laporan Pembelian & Pasokan')

@section('content')
@include('admin.laporan._navigasi')

<div class="card" style="margin-bottom: 24px; padding: 20px 24px;">
  <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px;">
    <!-- Period Filters -->
    <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
      <span style="font-size: 13px; font-weight: 700; color: var(--text-dark);">Periode:</span>
      <a href="{{ route('admin.reports.purchases', ['period' => 'today']) }}" class="btn btn-sm {{ $period === 'today' ? 'btn-primary' : 'btn-secondary' }}">Hari Ini</a>
      <a href="{{ route('admin.reports.purchases', ['period' => 'week']) }}" class="btn btn-sm {{ $period === 'week' ? 'btn-primary' : 'btn-secondary' }}">Minggu Ini</a>
      <a href="{{ route('admin.reports.purchases', ['period' => 'month']) }}" class="btn btn-sm {{ $period === 'month' ? 'btn-primary' : 'btn-secondary' }}">Bulan Ini</a>
    </div>

    <!-- Toggle & Actions -->
    <div style="display: flex; gap: 12px; align-items: center;">
      <div class="report-toggle-group">
        <button type="button" id="btnShowChart" class="report-toggle-btn active">📊 Mode Chart</button>
        <button type="button" id="btnShowTable" class="report-toggle-btn">📋 Mode Data Mentah</button>
      </div>

      <a href="{{ route('admin.ai.analyze', ['prompt' => 'Evaluasi pengeluaran pembelian bahan baku dari supplier dan efisiensi modal Warmindo']) }}" class="btn btn-primary btn-sm" style="background-color: #6366F1; border-color: #6366F1;">
        ✨ Analisis dengan AI
      </a>
    </div>
  </div>
</div>

<div class="stat-grid" style="margin-bottom: 24px;">
  <div class="stat-card">
    <div>
      <div class="stat-label">Total Belanja Pasokan (HPP)</div>
      <div class="stat-value" style="color: var(--primary);">Rp {{ number_format($totalPurchaseAmount, 0, ',', '.') }}</div>
    </div>
    <div class="stat-icon orange">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    </div>
  </div>
</div>

<!-- 1. MODE CHART -->
<div id="reportChartSection" class="card" style="margin-bottom: 24px;">
  <div class="card-header">
    <div>
      <h3 class="card-title">Grafik Alokasi Pembelian per Supplier</h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Distribusi nilai belanja bahan baku ke vendor supplier</p>
    </div>
  </div>
  <div style="height: 320px; position: relative;">
    <canvas id="purchasesReportChart"></canvas>
  </div>
</div>

<!-- 2. MODE DATA MENTAH -->
<div id="reportTableSection" class="card" style="padding: 0; overflow: hidden; display: none;">
  <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-light);">
    <h3 class="card-title">Tabel Data Mentah Pembelian</h3>
  </div>

  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px;">No. Pembelian</th>
          <th>Tanggal</th>
          <th>Nama Supplier</th>
          <th>Item Barang</th>
          <th style="text-align: right; padding-right: 24px;">Total Nilai</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rawRecords as $rec)
          <tr>
            <td style="padding-left: 24px; font-weight: 700; color: var(--primary);">#{{ $rec->purchase_number }}</td>
            <td>{{ $rec->purchase_date->format('d M Y') }}</td>
            <td style="font-weight: 700;">{{ $rec->supplier->name ?? '-' }}</td>
            <td style="font-size: 13px;">{{ $rec->items->count() }} jenis item</td>
            <td style="text-align: right; font-weight: 800; padding-right: 24px;">{{ $rec->formatted_total }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align: center; padding: 36px; color: var(--text-muted);">
              Tidak ada data pembelian pada periode ini.
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
  const ctx = document.getElementById('purchasesReportChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: {!! json_encode($chartLabels) !!},
      datasets: [{
        label: 'Total Belanja (Rp)',
        data: {!! json_encode($chartValues) !!},
        backgroundColor: '#1C7ED6',
        borderRadius: 8,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
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
