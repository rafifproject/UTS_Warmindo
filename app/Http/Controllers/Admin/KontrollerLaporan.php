<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\ItemPenjualan;
use App\Models\Produk;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KontrollerLaporan extends Controller
{
    private function dapatkanRentangTanggal(Request $request)
    {
        $period = $request->query('period', 'month'); // today, week, month, custom
        $startDate = null;
        $endDate = null;

        if ($period === 'today') {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        } elseif ($period === 'week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } elseif ($period === 'month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } elseif ($period === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
        } else {
            $startDate = Carbon::now()->subDays(30)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        }

        return [$startDate, $endDate, $period];
    }

    public function indeks(Request $request)
    {
        return redirect()->route('admin.reports.sales');
    }

    public function index(Request $request)
    {
        return $this->indeks($request);
    }

    // 1. Laporan Penjualan
    public function penjualan(Request $request)
    {
        [$startDate, $endDate, $period] = $this->dapatkanRentangTanggal($request);

        $salesQuery = Penjualan::where('payment_status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate]);

        $totalRevenue = (clone $salesQuery)->sum('total_amount');
        $totalOrders = (clone $salesQuery)->count();
        $rawRecords = (clone $salesQuery)->with('itemPenjualan')->latest('paid_at')->paginate(15)->withQueryString();

        // Chart Grouping by Day
        $chartDataGroup = Penjualan::where('payment_status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(paid_at) as date'), DB::raw('sum(total_amount) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartLabels = [];
        $chartValues = [];
        foreach ($chartDataGroup as $d) {
            $chartLabels[] = Carbon::parse($d->date)->format('d M');
            $chartValues[] = (float)$d->total;
        }

        $activeReport = 'sales';
        $reportTitle = 'Laporan Penjualan';

        return view('admin.laporan.penjualan', compact(
            'rawRecords',
            'chartLabels',
            'chartValues',
            'totalRevenue',
            'totalOrders',
            'startDate',
            'endDate',
            'period',
            'activeReport',
            'reportTitle'
        ));
    }

    public function sales(Request $request)
    {
        return $this->penjualan($request);
    }

    // 2. Laporan Transaksi
    public function transaksi(Request $request)
    {
        [$startDate, $endDate, $period] = $this->dapatkanRentangTanggal($request);

        $query = Penjualan::whereBetween('created_at', [$startDate, $endDate]);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('payment_status', $request->status);
        }

        $rawRecords = (clone $query)->with(['itemPenjualan', 'pengguna'])->latest()->paginate(15)->withQueryString();

        // Group by status for chart
        $statusChart = Penjualan::whereBetween('created_at', [$startDate, $endDate])
            ->select('payment_status', DB::raw('count(*) as count'))
            ->groupBy('payment_status')
            ->get();

        $chartLabels = [];
        $chartValues = [];
        foreach ($statusChart as $s) {
            $chartLabels[] = strtoupper($s->payment_status);
            $chartValues[] = (int)$s->count;
        }

        $activeReport = 'transactions';
        $reportTitle = 'Laporan Transaksi';

        return view('admin.laporan.transaksi', compact(
            'rawRecords',
            'chartLabels',
            'chartValues',
            'startDate',
            'endDate',
            'period',
            'activeReport',
            'reportTitle'
        ));
    }

    public function transactions(Request $request)
    {
        return $this->transaksi($request);
    }

    // 3. Laporan Menu Terlaris
    public function menuTerlaris(Request $request)
    {
        [$startDate, $endDate, $period] = $this->dapatkanRentangTanggal($request);

        $topItems = ItemPenjualan::whereHas('penjualan', function ($sq) use ($startDate, $endDate) {
                $sq->where('payment_status', 'paid')
                   ->whereBetween('paid_at', [$startDate, $endDate]);
            })
            ->select('product_name', DB::raw('sum(quantity) as total_qty'), DB::raw('sum(subtotal) as total_revenue'))
            ->groupBy('product_name')
            ->orderBy('total_qty', 'desc')
            ->get();

        $chartLabels = $topItems->pluck('product_name')->take(7)->toArray();
        $chartValues = $topItems->pluck('total_qty')->take(7)->toArray();

        $activeReport = 'top_menus';
        $reportTitle = 'Laporan Menu Terlaris';

        return view('admin.laporan.menu-terlaris', compact(
            'topItems',
            'chartLabels',
            'chartValues',
            'startDate',
            'endDate',
            'period',
            'activeReport',
            'reportTitle'
        ));
    }

    public function topMenus(Request $request)
    {
        return $this->menuTerlaris($request);
    }

    // 4. Laporan Stok
    public function stok()
    {
        $products = Produk::with('kategori')->orderBy('stock', 'asc')->get();

        $chartLabels = $products->pluck('name')->take(8)->toArray();
        $chartStock = $products->pluck('stock')->take(8)->toArray();
        $chartMinStock = $products->pluck('min_stock')->take(8)->toArray();

        $activeReport = 'stock';
        $reportTitle = 'Laporan Stok & Batas Minimum';

        return view('admin.laporan.stok', compact(
            'products',
            'chartLabels',
            'chartStock',
            'chartMinStock',
            'activeReport',
            'reportTitle'
        ));
    }

    public function stock()
    {
        return $this->stok();
    }

    // 5. Laporan Pembelian
    public function pembelian(Request $request)
    {
        [$startDate, $endDate, $period] = $this->dapatkanRentangTanggal($request);

        $purchasesQuery = Pembelian::whereBetween('purchase_date', [$startDate, $endDate]);

        $totalPurchaseAmount = (clone $purchasesQuery)->sum('total_amount');
        $rawRecords = (clone $purchasesQuery)->with(['pemasok', 'itemPembelian.produk'])->latest('purchase_date')->paginate(15)->withQueryString();

        // Chart by supplier
        $supplierGroup = Pembelian::whereBetween('purchase_date', [$startDate, $endDate])
            ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->select('suppliers.name as supplier_name', DB::raw('sum(total_amount) as total'))
            ->groupBy('suppliers.name')
            ->get();

        $chartLabels = $supplierGroup->pluck('supplier_name')->toArray();
        $chartValues = $supplierGroup->pluck('total')->toArray();

        $activeReport = 'purchases';
        $reportTitle = 'Laporan Pembelian & Pasokan';

        return view('admin.laporan.pembelian', compact(
            'rawRecords',
            'chartLabels',
            'chartValues',
            'totalPurchaseAmount',
            'startDate',
            'endDate',
            'period',
            'activeReport',
            'reportTitle'
        ));
    }

    public function purchases(Request $request)
    {
        return $this->pembelian($request);
    }

    // 6. Laporan Dine In vs Take Away
    public function makanVsBungkus(Request $request)
    {
        [$startDate, $endDate, $period] = $this->dapatkanRentangTanggal($request);

        $query = Penjualan::where('payment_status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate]);

        $dineInCount = (clone $query)->where('order_type', 'dine_in')->count();
        $dineInRevenue = (clone $query)->where('order_type', 'dine_in')->sum('total_amount');

        $takeAwayCount = (clone $query)->where('order_type', 'take_away')->count();
        $takeAwayRevenue = (clone $query)->where('order_type', 'take_away')->sum('total_amount');

        $rawRecords = (clone $query)->with('itemPenjualan')->latest('paid_at')->paginate(15)->withQueryString();

        $chartLabels = ['Dine In (Makan di Tempat)', 'Take Away (Bungkus)'];
        $chartValues = [$dineInCount, $takeAwayCount];

        $activeReport = 'dine_in_vs_take_away';
        $reportTitle = 'Laporan Dine In vs Take Away';

        return view('admin.laporan.makan-vs-bungkus', compact(
            'rawRecords',
            'dineInCount',
            'dineInRevenue',
            'takeAwayCount',
            'takeAwayRevenue',
            'chartLabels',
            'chartValues',
            'startDate',
            'endDate',
            'period',
            'activeReport',
            'reportTitle'
        ));
    }

    public function dineInVsTakeAway(Request $request)
    {
        return $this->makanVsBungkus($request);
    }

    // 7. Laporan Pembayaran
    public function pembayaran(Request $request)
    {
        [$startDate, $endDate, $period] = $this->dapatkanRentangTanggal($request);

        $query = Penjualan::where('payment_status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate]);

        $rawRecords = (clone $query)->latest('paid_at')->paginate(15)->withQueryString();

        // Group by payment method
        $pmGroup = (clone $query)
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        $chartLabels = [];
        $chartValues = [];
        foreach ($pmGroup as $pm) {
            $chartLabels[] = strtoupper($pm->payment_method ?? 'TUNAI');
            $chartValues[] = (float)$pm->total;
        }

        $activeReport = 'payments';
        $reportTitle = 'Laporan Pembayaran & Omzet';

        return view('admin.laporan.pembayaran', compact(
            'rawRecords',
            'chartLabels',
            'chartValues',
            'startDate',
            'endDate',
            'period',
            'activeReport',
            'reportTitle'
        ));
    }

    public function payments(Request $request)
    {
        return $this->pembayaran($request);
    }

    // CSV Exporter for Raw Data
    public function eksporCsv(Request $request, $type)
    {
        [$startDate, $endDate] = $this->dapatkanRentangTanggal($request);
        $filename = "laporan_{$type}_" . Carbon::now()->format('Ymd_His') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($type, $startDate, $endDate) {
            $handle = fopen('php://output', 'w');

            if ($type === 'sales') {
                fputcsv($handle, ['No Pesanan', 'Tanggal', 'Jenis Layanan', 'Meja', 'Total', 'Metode Bayar']);
                $sales = Penjualan::where('payment_status', 'paid')->whereBetween('paid_at', [$startDate, $endDate])->get();
                foreach ($sales as $s) {
                    fputcsv($handle, [$s->sale_number, $s->paid_at->format('Y-m-d H:i'), $s->order_type, $s->table_number ?? '-', $s->total_amount, $s->payment_method]);
                }
            } elseif ($type === 'stock') {
                fputcsv($handle, ['SKU', 'Nama Menu', 'Kategori', 'Stok Saat Ini', 'Batas Minimum', 'Harga Beli', 'Harga Jual']);
                $products = Produk::with('kategori')->get();
                foreach ($products as $p) {
                    fputcsv($handle, [$p->sku, $p->name, $p->kategori->name ?? '-', $p->stock, $p->min_stock, $p->buy_price, $p->sell_price]);
                }
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportCsv(Request $request, $type)
    {
        return $this->eksporCsv($request, $type);
    }
}
