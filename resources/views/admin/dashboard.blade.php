@extends('layouts.admin')

@section('title', 'Dashboard Owner — Warmindo')
@section('page_title', 'Dashboard Ringkasan Usaha')

@section('content')
<!-- Stat Cards (Screen 3: Total Produk, Total Transaksi, Total Penjualan) -->
<div class="stat-grid">
  <div class="stat-card">
    <div>
      <div class="stat-label">Total Produk Menu</div>
      <div class="stat-value">{{ $totalProducts }}</div>
      <span style="font-size: 11.5px; color: #12B886; font-weight: 600;">Aktif di katalog</span>
    </div>
    <div class="stat-icon orange">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Total Transaksi Lunas</div>
      <div class="stat-value">{{ $totalTransactions }}</div>
      <span style="font-size: 11.5px; color: #12B886; font-weight: 600;">Pesanan berhasil</span>
    </div>
    <div class="stat-icon green">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>
  </div>

  <div class="stat-card">
    <div>
      <div class="stat-label">Total Akumulasi Penjualan</div>
      <div class="stat-value" style="color: var(--primary);">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
      <span style="font-size: 11.5px; color: #12B886; font-weight: 600;">Omzet kotor usaha</span>
    </div>
    <div class="stat-icon blue">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    </div>
  </div>
</div>

<!-- Charts Section: Penjualan 7 Hari & Metode Pembayaran (Screen 3) -->
<div style="display: flex; flex-wrap: wrap; gap: 24px; margin-bottom: 28px;">
  <!-- Line Chart: Penjualan 7 Hari Terakhir -->
  <div class="card" style="flex: 2; min-width: 320px;">
    <div class="card-header">
      <div>
        <h3 class="card-title">Tren Penjualan 7 Hari Terakhir</h3>
        <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Fluktuasi omzet harian Warmindo</p>
      </div>
      <a href="{{ route('admin.reports.sales') }}" style="font-size: 13px; font-weight: 700; color: var(--primary);">Laporan Lengkap &rarr;</a>
    </div>
    <div style="height: 260px; position: relative;">
      <canvas id="salesTrendChart"></canvas>
    </div>
  </div>

  <!-- Doughnut Chart: Metode Pembayaran -->
  <div class="card" style="flex: 1.1; min-width: 280px;">
    <div class="card-header">
      <div>
        <h3 class="card-title">Metode Pembayaran</h3>
        <p style="font-size: 12.5px; color: var(--text-muted); margin: 0;">Proporsi transaksi masuk</p>
      </div>
    </div>
    <div style="height: 220px; position: relative; display: flex; align-items: center; justify-content: center;">
      <canvas id="paymentMethodsChart"></canvas>
    </div>
  </div>
</div>

<!-- Bottom Section: Produk Stok Menipis & Transaksi Terbaru (Screen 3) -->
<div style="display: flex; flex-wrap: wrap; gap: 24px;">
  <!-- Produk Stok Menipis Alert Table -->
  <div class="card" style="flex: 1; min-width: 320px; padding: 0; overflow: hidden;">
    <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
      <h3 style="font-size: 15px; font-weight: 800; color: #E03131; display: flex; align-items: center; gap: 6px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        Peringatan Stok Menipis
      </h3>
      <a href="{{ route('admin.products.low-stock') }}" style="font-size: 12.5px; font-weight: 700; color: var(--primary);">Lihat Semua &rarr;</a>
    </div>

    <div class="table-responsive">
      <table class="table" style="margin: 0;">
        <thead>
          <tr>
            <th style="padding-left: 24px;">Nama Produk</th>
            <th>Kategori</th>
            <th style="text-align: center;">Sisa Stok</th>
            <th style="text-align: center;">Min</th>
            <th style="padding-right: 24px;">Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($lowStockProducts as $low)
            <tr>
              <td style="padding-left: 24px; font-weight: 700; color: var(--text-dark);">
                {{ $low->name }}
              </td>
              <td>{{ $low->category->name ?? '-' }}</td>
              <td style="text-align: center; font-weight: 800; color: #FA5252;">
                {{ $low->stock }}
              </td>
              <td style="text-align: center; color: var(--text-muted);">
                {{ $low->min_stock }}
              </td>
              <td style="padding-right: 24px;">
                <span class="badge badge-red">Kritis</span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align: center; padding: 24px; color: var(--text-muted);">
                Semua stok produk saat ini aman.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Transaksi Terbaru Table -->
  <div class="card" style="flex: 1.2; min-width: 320px; padding: 0; overflow: hidden;">
    <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
      <h3 style="font-size: 15px; font-weight: 800; color: var(--text-dark);">Transaksi Terbaru</h3>
      <a href="{{ route('admin.sales.index') }}" style="font-size: 12.5px; font-weight: 700; color: var(--primary);">Lihat Semua &rarr;</a>
    </div>

    <div class="table-responsive">
      <table class="table" style="margin: 0;">
        <thead>
          <tr>
            <th style="padding-left: 24px;">No. Pesanan</th>
            <th>Layanan</th>
            <th>Total</th>
            <th>Waktu</th>
            <th style="text-align: right; padding-right: 24px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($recentSales as $sale)
            <tr>
              <td style="padding-left: 24px; font-weight: 700; color: var(--primary);">
                #{{ $sale->sale_number }}
              </td>
              <td>
                {{ $sale->order_type === 'dine_in' ? 'Meja ' . $sale->table_number : 'Take Away' }}
              </td>
              <td style="font-weight: 700;">
                {{ $sale->formatted_total }}
              </td>
              <td style="color: var(--text-muted); font-size: 12.5px;">
                {{ $sale->paid_at ? $sale->paid_at->format('d/m H:i') : '-' }}
              </td>
              <td style="text-align: right; padding-right: 24px;">
                <a href="{{ route('admin.sales.show', $sale->id) }}" class="btn btn-secondary btn-sm">Detail</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // 1. Sales Trend Line Chart
  const ctxSales = document.getElementById('salesTrendChart').getContext('2d');
  new Chart(ctxSales, {
    type: 'line',
    data: {
      labels: {!! json_encode($labels7Days) !!},
      datasets: [{
        label: 'Omzet Penjualan (Rp)',
        data: {!! json_encode($sales7Days) !!},
        borderColor: '#F06524',
        backgroundColor: 'rgba(240, 101, 36, 0.08)',
        borderWidth: 3,
        fill: true,
        tension: 0.35,
        pointBackgroundColor: '#FFFFFF',
        pointBorderColor: '#F06524',
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(context) {
              return 'Rp ' + context.raw.toLocaleString('id-ID');
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: { color: '#F1F3F5' },
          ticks: {
            callback: function(val) {
              return 'Rp ' + (val / 1000) + 'k';
            }
          }
        },
        x: {
          grid: { display: false }
        }
      }
    }
  });

  // 2. Payment Method Doughnut Chart
  const ctxPay = document.getElementById('paymentMethodsChart').getContext('2d');
  new Chart(ctxPay, {
    type: 'doughnut',
    data: {
      labels: {!! json_encode($payLabels) !!},
      datasets: [{
        data: {!! json_encode($payCounts) !!},
        backgroundColor: ['#F06524', '#12B886', '#1C7ED6', '#FAB005'],
        borderWidth: 2,
        borderColor: '#FFFFFF',
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: { boxWidth: 12, font: { size: 12 } }
        }
      },
      cutout: '70%'
    }
  });
</script>
@endpush
