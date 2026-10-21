@extends('layouts.admin')

@section('title', 'Laporan Pembayaran & Omzet — Warmindo')
@section('page_title', 'Laporan Pembayaran & Omzet')

@section('content')
@include('admin.reports._nav')

<div class="card" style="margin-bottom: 24px; padding: 20px 24px;">
  <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px;">
    <!-- Period Filters -->
    <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
      <span style="font-size: 13px; font-weight: 700; color: var(--text-dark);">Periode:</span>
      <a href="{{ route('admin.reports.payments', ['period' => 'today']) }}" class="btn btn-sm {{ $period === 'today' ? 'btn-primary' : 'btn-secondary' }}">Hari Ini</a>
      <a href="{{ route('admin.reports.payments', ['period' => 'week']) }}" class="btn btn-sm {{ $period === 'week' ? 'btn-primary' : 'btn-secondary' }}">Minggu Ini</a>
      <a href="{{ route('admin.reports.payments', ['period' => 'month']) }}" class="btn btn-sm {{ $period === 'month' ? 'btn-primary' : 'btn-secondary' }}">Bulan Ini</a>
    </div>

    <!-- Toggle & Actions -->
    <div style="display: flex; gap: 12px; align-items: center;">
      <div class="report-toggle-group">
        <button type="button" id="btnShowChart" class="report-toggle-btn active">📊 Mode Chart</button>
        <button type="button" id="btnShowTable" class="report-toggle-btn">📋 Mode Data Mentah</button>
      </div>

      <a href="{{ route('admin.ai.analyze', ['prompt' => 'Analisis adopsi metode pembayaran tunai kasir vs QRIS non-tunai di Warmindo']) }}" class="btn btn-primary btn-sm" style="background-color: #6366F1; border-color: #6366F1;">
        ✨ Analisis dengan AI
      </a>
    </div>
  </div>
</div>

<!-- 1. MODE CHART -->
<div id="reportChartSection" class="card" style="margin-bottom: 24px;">
  <div class="card-header">
    <div>
      <h3 class="card-title">Grafik Nilai Omzet per Metode Pembayaran</h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Total rupiah yang masuk melalui Tunai, QRIS, dan Transfer</p>
    </div>
  </div>
  <div style="height: 320px; position: relative;">
    <canvas id="paymentMethodsBarChart"></canvas>
  </div>
</div>

<!-- 2. MODE DATA MENTAH -->
<div id="reportTableSection" class="card" style="padding: 0; overflow: hidden; display: none;">
  <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-light);">
    <h3 class="card-title">Tabel Data Mentah Pembayaran</h3>
  </div>

  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px;">No. Pesanan</th>
          <th>Metode Bayar</th>
          <th>Waktu Pembayaran</th>
          <th style="text-align: right;">Total Transaksi</th>
          <th style="text-align: right;">Uang Diterima</th>
          <th style="text-align: right;">Kembalian</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rawRecords as $rec)
          <tr>
            <td style="padding-left: 24px; font-weight: 700; color: var(--primary);">#{{ $rec->sale_number }}</td>
            <td>
              <span class="badge {{ $rec->payment_method === 'cash' ? 'badge-green' : 'badge-blue' }}" style="text-transform: uppercase;">
                {{ $rec->payment_method ?? 'TUNAI' }}
              </span>
            </td>
            <td style="font-size: 13px; color: var(--text-dark);">
              {{ $rec->paid_at ? $rec->paid_at->format('d/m/Y H:i') : '-' }}
            </td>
            <td style="text-align: right; font-weight: 800; color: var(--text-dark);">
              {{ $rec->formatted_total }}
            </td>
            <td style="text-align: right; font-size: 13px;">
              Rp {{ number_format($rec->cash_received ?? $rec->total_amount, 0, ',', '.') }}
            </td>
            <td style="text-align: right; font-size: 13px; color: #0CA678; font-weight: 600;">
              Rp {{ number_format($rec->cash_change ?? 0, 0, ',', '.') }}
            </td>
            <td><span class="badge badge-green">Lunas</span></td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align: center; padding: 36px; color: var(--text-muted);">
              Tidak ada data pembayaran.
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
  const ctx = document.getElementById('paymentMethodsBarChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: {!! json_encode($chartLabels) !!},
      datasets: [{
        label: 'Nilai Transaksi (Rp)',
        data: {!! json_encode($chartValues) !!},
        backgroundColor: ['#0CA678', '#1C7ED6', '#F06524'],
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
