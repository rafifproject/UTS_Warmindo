@extends('layouts.admin')

@section('title', 'Laporan Stok & Batas Minimum — Warmindo')
@section('page_title', 'Laporan Stok & Batas Minimum')

@section('content')
@include('admin.reports._nav')

<div class="card" style="margin-bottom: 24px; padding: 20px 24px;">
  <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px;">
    <div>
      <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Inventori Persediaan Menu</h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Pantau ketersediaan stok fisik terhadap batas ambang aman minimum</p>
    </div>

    <!-- Toggle & Actions -->
    <div style="display: flex; gap: 12px; align-items: center;">
      <div class="report-toggle-group">
        <button type="button" id="btnShowChart" class="report-toggle-btn active">📊 Mode Chart</button>
        <button type="button" id="btnShowTable" class="report-toggle-btn">📋 Mode Data Mentah</button>
      </div>

      <a href="{{ route('admin.reports.export', ['type' => 'stock']) }}" class="btn btn-secondary btn-sm">
        Ekspor CSV
      </a>

      <a href="{{ route('admin.ai.analyze', ['prompt' => 'Analisis ketersediaan stok menu Warmindo dan berikan rekomendasi pembelian barang menipis']) }}" class="btn btn-primary btn-sm" style="background-color: #6366F1; border-color: #6366F1;">
        ✨ Analisis dengan AI
      </a>
    </div>
  </div>
</div>

<!-- 1. MODE CHART: Grouped Bar Chart Stok vs Min Stock -->
<div id="reportChartSection" class="card" style="margin-bottom: 24px;">
  <div class="card-header">
    <div>
      <h3 class="card-title">Perbandingan Stok Aktual vs Batas Minimum</h3>
      <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Merah menandakan stok mendekati atau di bawah batas aman</p>
    </div>
  </div>
  <div style="height: 350px; position: relative;">
    <canvas id="stockComparisonChart"></canvas>
  </div>
</div>

<!-- 2. MODE DATA MENTAH -->
<div id="reportTableSection" class="card" style="padding: 0; overflow: hidden; display: none;">
  <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-light);">
    <h3 class="card-title">Tabel Data Mentah Stok Produk</h3>
  </div>

  <div class="table-responsive">
    <table class="table" style="margin: 0;">
      <thead>
        <tr>
          <th style="padding-left: 24px;">SKU</th>
          <th>Nama Menu</th>
          <th>Kategori</th>
          <th style="text-align: center;">Stok Tersedia</th>
          <th style="text-align: center;">Batas Minimum</th>
          <th>Status Keamanan</th>
          <th style="text-align: right; padding-right: 24px;">Harga Beli</th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $p)
          <tr>
            <td style="padding-left: 24px;"><code>{{ $p->sku }}</code></td>
            <td style="font-weight: 700; color: var(--text-dark);">{{ $p->name }}</td>
            <td><span class="badge badge-blue">{{ $p->category->name ?? '-' }}</span></td>
            <td style="text-align: center; font-weight: 800; color: {{ $p->stock <= $p->min_stock ? '#FA5252' : '#0CA678' }};">
              {{ $p->stock }} porsi
            </td>
            <td style="text-align: center; color: var(--text-muted);">{{ $p->min_stock }} porsi</td>
            <td>
              @if($p->stock <= $p->min_stock)
                <span class="badge badge-red">⚠️ Kritis / Menipis</span>
              @else
                <span class="badge badge-green">&check; Aman</span>
              @endif
            </td>
            <td style="text-align: right; font-weight: 600; padding-right: 24px;">{{ $p->formatted_buy_price }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const ctx = document.getElementById('stockComparisonChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: {!! json_encode($chartLabels) !!},
      datasets: [
        {
          label: 'Stok Saat Ini',
          data: {!! json_encode($chartStock) !!},
          backgroundColor: '#0CA678',
          borderRadius: 6,
        },
        {
          label: 'Batas Minimum',
          data: {!! json_encode($chartMinStock) !!},
          backgroundColor: '#FA5252',
          borderRadius: 6,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>
@endpush
