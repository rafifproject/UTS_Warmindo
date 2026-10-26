<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\ItemPenjualan;
use App\Models\Produk;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class KontrollerAsistenAi extends Controller
{
    public function indeks()
    {
        $metrics = $this->kumpulkanMetrik();
        $autoAnalysis = $this->buatAnalisisOtomatis($metrics);

        return view('admin.asisten-ai.indeks', compact('metrics', 'autoAnalysis'));
    }

    public function index()
    {
        return $this->indeks();
    }

    public function analisis(Request $request)
    {
        $question = $request->input('prompt', 'Berikan evaluasi performa bisnis Warmindo minggu ini dan rekomendasi strategis.');
        $metrics = $this->kumpulkanMetrik();

        // 1. Cek API Key eksternal di .env
        $geminiKey = env('GEMINI_API_KEY');
        $openaiKey = env('OPENAI_API_KEY');

        $aiResponse = null;

        if ($geminiKey) {
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$geminiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => "Anda adalah AI Business Assistant ahli untuk Owner Warmindo (warung makan Indomie). Analisis data metrik operasional berikut:\n" .
                                        json_encode($metrics, JSON_PRETTY_PRINT) .
                                        "\nJawab pertanyaan Owner dengan gaya profesional, ramah, dan actionable: " . $question
                                ]
                            ]
                        ]
                    ]
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $aiResponse = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
                }
            } catch (\Exception $e) {
                // Fallback otomatis
            }
        }

        // 2. Intelligent Data-Backed Local Analytic Engine Fallback
        if (!$aiResponse) {
            $aiResponse = $this->buatResponKontekstual($question, $metrics);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'response' => $aiResponse,
                'metrics' => $metrics,
            ]);
        }

        $autoAnalysis = $this->buatAnalisisOtomatis($metrics);
        return view('admin.asisten-ai.indeks', compact('metrics', 'autoAnalysis', 'aiResponse', 'question'));
    }

    public function analyze(Request $request)
    {
        return $this->analisis($request);
    }

    private function kumpulkanMetrik(): array
    {
        $now = Carbon::now();
        $startOfWeek = Carbon::now()->startOfWeek();

        $totalSalesWeek = Penjualan::where('payment_status', 'paid')
            ->where('paid_at', '>=', $startOfWeek)
            ->sum('total_amount');

        $totalOrdersWeek = Penjualan::where('payment_status', 'paid')
            ->where('paid_at', '>=', $startOfWeek)
            ->count();

        $avgOrderValue = $totalOrdersWeek > 0 ? round($totalSalesWeek / $totalOrdersWeek) : 0;

        $topSelling = ItemPenjualan::whereHas('penjualan', function ($q) use ($startOfWeek) {
                $q->where('payment_status', 'paid')->where('paid_at', '>=', $startOfWeek);
            })
            ->select('product_name', DB::raw('sum(quantity) as qty'), DB::raw('sum(subtotal) as revenue'))
            ->groupBy('product_name')
            ->orderBy('qty', 'desc')
            ->first();

        $lowStockCount = Produk::whereColumn('stock', '<=', 'min_stock')->count();
        $lowStockNames = Produk::whereColumn('stock', '<=', 'min_stock')->pluck('name')->toArray();

        $dineInCount = Penjualan::where('payment_status', 'paid')->where('order_type', 'dine_in')->count();
        $takeAwayCount = Penjualan::where('payment_status', 'paid')->where('order_type', 'take_away')->count();
        $dineInPercentage = ($dineInCount + $takeAwayCount) > 0 ? round(($dineInCount / ($dineInCount + $takeAwayCount)) * 100) : 0;

        return [
            'period' => 'Minggu Ini (' . $startOfWeek->format('d M') . ' - ' . $now->format('d M Y') . ')',
            'omzet_mingguan' => 'Rp ' . number_format($totalSalesWeek, 0, ',', '.'),
            'total_transaksi' => $totalOrdersWeek,
            'rata_rata_transaksi' => 'Rp ' . number_format($avgOrderValue, 0, ',', '.'),
            'menu_terlaris' => $topSelling ? "{$topSelling->product_name} ({$topSelling->qty} porsi)" : 'Belum ada data',
            'jumlah_stok_menipis' => $lowStockCount,
            'daftar_stok_menipis' => implode(', ', $lowStockNames),
            'rasio_layanan' => "Dine In {$dineInPercentage}% vs Take Away " . (100 - $dineInPercentage) . "%",
        ];
    }

    private function buatAnalisisOtomatis(array $m): array
    {
        return [
            'ringkasan' => "Performa Warmindo pada periode {$m['period']} mencatatkan total omzet {$m['omzet_mingguan']} dari {$m['total_transaksi']} transaksi berhasil, dengan rata-rata belanja Rp {$m['rata_rata_transaksi']} per pesanan.",
            'kelebihan' => [
                "Menu unggulan utama adalah {$m['menu_terlaris']} dengan kontribusi volume terbesar.",
                "Dominasi layanan: {$m['rasio_layanan']}, menunjukkan pelanggan sangat menyukai suasana makan langsung di outlet.",
            ],
            'perhatian' => [
                $m['jumlah_stok_menipis'] > 0
                    ? "Terdapat {$m['jumlah_stok_menipis']} produk dengan stok mendekati batas minimum ({$m['daftar_stok_menipis']}). Segera lakukan pembelian stok ke supplier."
                    : "Semua stok produk saat ini dalam batas aman.",
                "Rata-rata transaksi dapat ditingkatkan lagi melalui paket bundling mie + minuman segar.",
            ],
            'rekomendasi' => [
                "Tingkatkan stok bahan baku untuk {$m['menu_terlaris']} agar tidak kehabisan di jam sibuk malam hari.",
                "Buat promo 'Paket Nasi/Mie + Es Teh/Jeruk Hemat' untuk menaikkan rata-rata nilai transaksi ke kisaran Rp 25.000.",
                "Optimalkan sistem antrean dapur di akhir pekan guna menekan waktu tunggu di bawah 10 menit.",
            ]
        ];
    }

    private function buatResponKontekstual(string $q, array $m): string
    {
        return "Halo Owner! Berdasarkan audit data riil sistem Warmindo:\n\n" .
            "1. **Kinerja Finansial**: Total omzet {$m['omzet_mingguan']} dari {$m['total_transaksi']} pesanan lunas. Nilai rata-rata per transaksi adalah {$m['rata_rata_transaksi']}.\n\n" .
            "2. **Menu Favorit & Pergerakan**: Menu primadona pelanggan adalah **{$m['menu_terlaris']}**.\n\n" .
            "3. **Peringatan Inventori**: " . ($m['jumlah_stok_menipis'] > 0 ? "Waspada! Stok bahan menipis pada **{$m['daftar_stok_menipis']}**. Segera buat PO pembelian ke supplier." : "Stok gudang masih sangat stabil.") . "\n\n" .
            "4. **Perilaku Konsumen**: Mayoritas memesan dengan skema **{$m['rasio_layanan']}**.\n\n" .
            "**Saran Strategis**: Pertahankan ketersediaan topping telur & sayur segar, dan terapkan teknik upsell minuman dingin saat customer melakukan checkout!";
    }
}
