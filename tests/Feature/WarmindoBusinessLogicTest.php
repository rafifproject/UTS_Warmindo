<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMutation;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WarmindoBusinessLogicTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test 1: Mengakses dashboard internal tanpa login diarahkan ke login
     */
    public function test_unauthenticated_user_cannot_access_internal_dashboards()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');

        $responseKasir = $this->get('/kasir');
        $responseKasir->assertRedirect('/login');
    }

    /**
     * Test 2: Login dengan password salah ditolak
     */
    public function test_login_with_invalid_credentials_fails()
    {
        $response = $this->post('/login', [
            'login' => 'admin@warmindo.id',
            'password' => 'wrong-password-123',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    /**
     * Test 3: Staff/Kasir dilarang membuka URL manajemen pengguna (Role Protection 403)
     */
    public function test_staff_cannot_access_user_management_receives_403()
    {
        $staff = User::where('role', 'staff')->first();
        if (!$staff) {
            $staff = User::create([
                'name' => 'Staff Kasir Test',
                'username' => 'stafftest',
                'email' => 'stafftest@warmindo.id',
                'password' => bcrypt('password'),
                'role' => 'staff',
                'status' => 'active',
            ]);
        }

        $response = $this->actingAs($staff)->get('/admin/users');
        $response->assertStatus(403);
    }

    /**
     * Test 4: Customer Dine In wajib mengisi nomor meja
     */
    public function test_dine_in_checkout_requires_table_number()
    {
        $product = Product::where('stock', '>', 5)->first();

        // 1. Put item in session cart
        $cart = [
            $product->id => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->sell_price,
                'quantity' => 2,
                'image' => $product->image,
            ]
        ];

        // 2. Submit service choice without table number
        $response = $this->withSession(['cart' => $cart])->post('/cart/service', [
            'order_type' => 'dine_in',
            'table_number' => '', // Empty table number
        ]);

        $response->assertSessionHasErrors('table_number');
    }

    /**
     * Test 5: Customer Take Away menyimpan nomor meja sebagai NULL
     */
    public function test_take_away_checkout_sets_table_number_to_null()
    {
        $product = Product::where('stock', '>', 5)->first();

        $cart = [
            $product->id => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->sell_price,
                'quantity' => 1,
                'image' => $product->image,
            ]
        ];

        // 1. Choose Take Away service
        $this->withSession(['cart' => $cart])->post('/cart/service', [
            'order_type' => 'take_away',
            'table_number' => '99', // should be reset to null
        ]);

        // 2. Checkout
        $response = $this->withSession([
            'cart' => $cart,
            'checkout_service' => [
                'order_type' => 'take_away',
                'table_number' => null,
            ]
        ])->post('/cart/checkout', [
            'notes' => 'Bungkus rapi ya',
        ]);

        $response->assertRedirect();

        $sale = Sale::where('order_type', 'take_away')->orderBy('id', 'desc')->first();
        $this->assertNotNull($sale);
        $this->assertNull($sale->table_number);
        $this->assertEquals('pending', $sale->payment_status);
        $this->assertEquals('waiting_payment', $sale->order_status);
    }

    /**
     * Test 6: Kasir menolak pembayaran jika uang yang diterima kurang dari total tagihan
     */
    public function test_cashier_rejects_insufficient_cash_payment()
    {
        $staff = User::where('role', 'staff')->first();
        $sale = Sale::create([
            'sale_number' => 'PSN-TEST-' . time(),
            'order_type' => 'dine_in',
            'table_number' => '10',
            'subtotal' => 30000,
            'service_fee' => 0,
            'total_amount' => 30000,
            'payment_status' => 'pending',
            'order_status' => 'waiting_payment',
        ]);

        $response = $this->actingAs($staff)->post("/kasir/pay/{$sale->sale_number}", [
            'cash_received' => 20000, // Kurang 10000
            'payment_method' => 'cash',
        ]);

        $response->assertSessionHas('show_insufficient_cash_modal', true);
        $response->assertSessionHas('shortage', 10000.0);
    }

    /**
     * Test 7: Kasir konfirmasi pembayaran cukup, stok berkurang atomik, status dapur queued, mutasi stok tercatat
     */
    public function test_cashier_payment_confirmation_reduces_stock_atomically()
    {
        $staff = User::where('role', 'staff')->first();
        $product = Product::where('stock', '>=', 10)->first();
        $initialStock = $product->stock;

        $sale = Sale::create([
            'sale_number' => 'PSN-STOCKTEST-' . time(),
            'order_type' => 'dine_in',
            'table_number' => '05',
            'subtotal' => $product->sell_price * 2,
            'service_fee' => 0,
            'total_amount' => $product->sell_price * 2,
            'payment_status' => 'pending',
            'order_status' => 'waiting_payment',
        ]);

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->sell_price,
            'quantity' => 2,
            'subtotal' => $product->sell_price * 2,
        ]);

        $cashReceived = ($product->sell_price * 2) + 10000;

        $response = $this->actingAs($staff)->post("/kasir/pay/{$sale->sale_number}", [
            'cash_received' => $cashReceived,
            'payment_method' => 'cash',
        ]);

        $response->assertRedirect();

        // Refresh model from database
        $sale->refresh();
        $product->refresh();

        $this->assertEquals('paid', $sale->payment_status);
        $this->assertEquals('queued', $sale->order_status);
        $this->assertEquals(10000, $sale->cash_change);
        $this->assertEquals($initialStock - 2, $product->stock);

        // Check stock mutation recorded
        $mutation = StockMutation::where('reference_type', 'sale')
            ->where('reference_id', $sale->id)
            ->where('product_id', $product->id)
            ->first();

        $this->assertNotNull($mutation);
        $this->assertEquals('out', $mutation->type);
        $this->assertEquals(2, $mutation->quantity);
    }

    /**
     * Test 8: Pembatalan pesanan lunas oleh Admin mengembalikan stok secara atomik
     */
    public function test_admin_cancel_paid_sale_restores_stock()
    {
        $admin = User::where('role', 'admin')->first();
        $product = Product::where('stock', '>=', 5)->first();
        $initialStock = $product->stock;

        $sale = Sale::create([
            'sale_number' => 'PSN-CANCELTEST-' . time(),
            'order_type' => 'dine_in',
            'table_number' => '02',
            'subtotal' => $product->sell_price,
            'service_fee' => 0,
            'total_amount' => $product->sell_price,
            'payment_status' => 'paid',
            'order_status' => 'queued',
            'paid_at' => now(),
        ]);

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->sell_price,
            'quantity' => 1,
            'subtotal' => $product->sell_price,
        ]);

        // Reduce stock as if it was already deducted on payment
        $product->decrement('stock', 1);

        $response = $this->actingAs($admin)->post("/admin/sales/{$sale->id}/cancel", [
            'reason' => 'Customer batal karena ada keperluan darurat',
        ]);

        $response->assertRedirect();

        $sale->refresh();
        $product->refresh();

        $this->assertEquals('cancelled', $sale->payment_status);
        $this->assertEquals($initialStock, $product->stock);
    }

    /**
     * Test 9: Laporan Penjualan dapat diakses oleh Admin dengan data konsisten
     */
    public function test_admin_can_access_reports()
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/reports/sales');
        $response->assertStatus(200);
        $response->assertSee('Laporan Penjualan');

        $responseTop = $this->actingAs($admin)->get('/admin/reports/top-menus');
        $responseTop->assertStatus(200);
        $responseTop->assertSee('Laporan Menu Terlaris');
    }

    /**
     * Test 10: AI Business Assistant dapat menganalisis data riil usaha
     */
    public function test_ai_business_assistant_generates_analysis()
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/ai-assistant');
        $response->assertStatus(200);
        $response->assertSee('AI Business Assistant');
        $response->assertSee('Data Pendukung Analisis');
    }

    /**
     * Test 11 (Slide 27 Butir 5): Purchase sukses menambah stok dan mencatat mutasi masuk
     */
    public function test_purchase_increases_stock_and_logs_mutation()
    {
        $admin = User::where('role', 'admin')->first();
        $supplier = \App\Models\Supplier::first();
        $product = Product::first();
        $initialStock = $product->stock;
        $qtyPurchased = 10;

        $response = $this->actingAs($admin)->post('/admin/purchases', [
            'purchase_number' => 'PB-TEST-' . time(),
            'supplier_id'     => $supplier->id,
            'purchase_date'   => now()->toDateString(),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity'   => $qtyPurchased,
                    'unit_price' => 8000,
                ]
            ],
            'notes' => 'Uji penambahan stok pembelian',
        ]);

        $response->assertRedirect('/admin/purchases');
        $product->refresh();

        $this->assertEquals($initialStock + $qtyPurchased, $product->stock);

        $mutation = StockMutation::where('reference_type', 'purchase')
            ->where('product_id', $product->id)
            ->latest('id')
            ->first();

        $this->assertNotNull($mutation);
        $this->assertEquals('in', $mutation->type);
        $this->assertEquals($qtyPurchased, $mutation->quantity);
    }

    /**
     * Test 12 (Slide 27 Butir 6): Sales dengan stok tidak cukup gagal dan rollback otomatis tanpa mengubah stok
     */
    public function test_sales_fails_and_rollbacks_when_stock_insufficient()
    {
        $staff = User::where('role', 'staff')->first();
        $product = Product::where('stock', '>', 0)->first();
        $initialStock = $product->stock;

        // Buat pesanan dengan kuantitas melebihi stok yang ada
        $excessQuantity = $initialStock + 50;

        $sale = Sale::create([
            'sale_number'    => 'PSN-OVERSOLD-' . time(),
            'order_type'     => 'dine_in',
            'table_number'   => '08',
            'subtotal'       => $product->sell_price * $excessQuantity,
            'service_fee'    => 0,
            'total_amount'   => $product->sell_price * $excessQuantity,
            'payment_status' => 'pending',
            'order_status'   => 'waiting_payment',
        ]);

        SaleItem::create([
            'sale_id'      => $sale->id,
            'product_id'   => $product->id,
            'product_name' => $product->name,
            'price'        => $product->sell_price,
            'quantity'     => $excessQuantity,
            'subtotal'     => $product->sell_price * $excessQuantity,
        ]);

        // Coba bayar di kasir, sistem menolak dengan pesan error dan me-rollback DB::transaction
        $response = $this->actingAs($staff)->post("/kasir/pay/{$sale->sale_number}", [
            'cash_received'  => ($product->sell_price * $excessQuantity) + 10000,
            'payment_method' => 'cash',
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Stok tidak mencukupi', session('error'));

        // Verifikasi Rollback: Stok fisik produk TIDAK BERUBAH sama sekali
        $product->refresh();
        $sale->refresh();

        $this->assertEquals($initialStock, $product->stock);
        $this->assertEquals('pending', $sale->payment_status);

        // Verifikasi tidak ada mutasi stok yang tersimpan
        $mutation = StockMutation::where('reference_type', 'sale')
            ->where('reference_id', $sale->id)
            ->first();
        $this->assertNull($mutation);
    }
}

