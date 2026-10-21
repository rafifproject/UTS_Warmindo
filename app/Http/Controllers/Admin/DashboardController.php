<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalTransactions = Sale::where('payment_status', 'paid')->count();
        $totalRevenue = Sale::where('payment_status', 'paid')->sum('total_amount');

        // 7 Days Sales Trend for Line Chart
        $sales7Days = [];
        $labels7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $labels7Days[] = $day->translatedFormat('d M');
            $dailySum = Sale::where('payment_status', 'paid')
                ->whereDate('paid_at', $day)
                ->sum('total_amount');
            $sales7Days[] = (float)$dailySum;
        }

        // Payment Method Proportion for Doughnut Chart
        $paymentMethods = Sale::where('payment_status', 'paid')
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        $payLabels = [];
        $payCounts = [];
        foreach ($paymentMethods as $pm) {
            $payLabels[] = strtoupper($pm->payment_method ?? 'TUNAI');
            $payCounts[] = (int)$pm->count;
        }

        // Low stock products alert table
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'min_stock')
            ->take(5)
            ->get();

        // Recent Transactions
        $recentSales = Sale::with('items')
            ->where('payment_status', 'paid')
            ->latest('paid_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalTransactions',
            'totalRevenue',
            'labels7Days',
            'sales7Days',
            'payLabels',
            'payCounts',
            'lowStockProducts',
            'recentSales'
        ));
    }
}
