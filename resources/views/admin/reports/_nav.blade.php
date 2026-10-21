<div style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 12px; margin-bottom: 24px; border-bottom: 1px solid var(--border-light);">
  <a href="{{ route('admin.reports.sales') }}" class="btn btn-sm {{ $activeReport === 'sales' ? 'btn-primary' : 'btn-secondary' }}">
    1. Laporan Penjualan
  </a>
  <a href="{{ route('admin.reports.transactions') }}" class="btn btn-sm {{ $activeReport === 'transactions' ? 'btn-primary' : 'btn-secondary' }}">
    2. Transaksi
  </a>
  <a href="{{ route('admin.reports.top-menus') }}" class="btn btn-sm {{ $activeReport === 'top_menus' ? 'btn-primary' : 'btn-secondary' }}">
    3. Menu Terlaris
  </a>
  <a href="{{ route('admin.reports.stock') }}" class="btn btn-sm {{ $activeReport === 'stock' ? 'btn-primary' : 'btn-secondary' }}">
    4. Stok &amp; Min
  </a>
  <a href="{{ route('admin.reports.purchases') }}" class="btn btn-sm {{ $activeReport === 'purchases' ? 'btn-primary' : 'btn-secondary' }}">
    5. Pembelian Pasokan
  </a>
  <a href="{{ route('admin.reports.dine-in-vs-take-away') }}" class="btn btn-sm {{ $activeReport === 'dine_in_vs_take_away' ? 'btn-primary' : 'btn-secondary' }}">
    6. Dine In vs Take Away
  </a>
  <a href="{{ route('admin.reports.payments') }}" class="btn btn-sm {{ $activeReport === 'payments' ? 'btn-primary' : 'btn-secondary' }}">
    7. Metode Bayar
  </a>
</div>
