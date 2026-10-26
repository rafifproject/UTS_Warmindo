<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMutation;
use App\Models\OrderStatusHistory;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $owner = User::create([
            'name' => 'Owner Warmindo',
            'username' => 'owner',
            'email' => 'owner@warmindo.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '0812-3456-7890',
            'status' => 'active',
        ]);

        $kasir = User::create([
            'name' => 'Siti Aisyah',
            'username' => 'siti',
            'email' => 'kasir@warmindo.id',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '0813-8877-6655',
            'status' => 'active',
        ]);

        $budi = User::create([
            'name' => 'Budi Santoso',
            'username' => 'budi',
            'email' => 'budi@warmindo.id',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '0812-9988-7711',
            'status' => 'active',
        ]);

        $rian = User::create([
            'name' => 'Rian Pratama',
            'username' => 'rian',
            'email' => 'rian@warmindo.id',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '0856-1122-3344',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Dini Lestari',
            'username' => 'dini',
            'email' => 'dini@warmindo.id',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '0878-4455-6677',
            'status' => 'inactive',
        ]);

        // 2. Categories
        $catMie = Category::create([
            'name' => 'Mie',
            'slug' => 'mie',
            'description' => 'Aneka olahan indomie dan mie hangat dengan racikan istimewa khas Warmindo',
            'is_active' => true,
        ]);

        $catMinuman = Category::create([
            'name' => 'Minuman',
            'slug' => 'minuman',
            'description' => 'Minuman dingin dan hangat pelepas dahaga dan penyegar suasana',
            'is_active' => true,
        ]);

        $catSnack = Category::create([
            'name' => 'Snack',
            'slug' => 'snack',
            'description' => 'Camilan dan gorengan gurih renyah teman setia ngobrol santai',
            'is_active' => true,
        ]);

        $catNasi = Category::create([
            'name' => 'Nasi',
            'slug' => 'nasi',
            'description' => 'Menu nasi lezat porsi pas bikin kenyang maksimal',
            'is_active' => true,
        ]);

        // 3. Products
        $productsData = [
            [
                'category_id' => $catMie->id,
                'sku' => 'WMW-001',
                'name' => 'Indomie Goreng',
                'slug' => 'indomie-goreng',
                'description' => 'Mie instan goreng dengan bumbu khas Indonesia, disajikan dengan telur, sayuran segar, dan taburan bawang goreng renyah.',
                'buy_price' => 8000,
                'sell_price' => 12000,
                'stock' => 25,
                'min_stock' => 5,
                'image' => 'images/menu/indomie-goreng.png',
                'is_available' => true,
                'is_favorite' => true,
            ],
            [
                'category_id' => $catMie->id,
                'sku' => 'WMW-002',
                'name' => 'Mie Ayam',
                'slug' => 'mie-ayam',
                'description' => 'Mie kenyal disajikan dengan potongan daging ayam bumbu kecap manis gurih, sawi hijau, dan kuah kaldu harum.',
                'buy_price' => 10000,
                'sell_price' => 15000,
                'stock' => 18,
                'min_stock' => 5,
                'image' => 'images/menu/mie-ayam.png',
                'is_available' => true,
                'is_favorite' => true,
            ],
            [
                'category_id' => $catMie->id,
                'sku' => 'WMW-003',
                'name' => 'Mie Kari',
                'slug' => 'mie-kari',
                'description' => 'Mie kuah kari kental berempah aroma menggugah selera, komplit dengan telur rebus dan irisan cabai rawit.',
                'buy_price' => 10000,
                'sell_price' => 15000,
                'stock' => 20,
                'min_stock' => 5,
                'image' => 'images/menu/mie-kari.png',
                'is_available' => true,
                'is_favorite' => false,
            ],
            [
                'category_id' => $catMie->id,
                'sku' => 'WMW-004',
                'name' => 'Mie Kuah',
                'slug' => 'mie-kuah',
                'description' => 'Mie kuah kaldu ayam spesial yang hangat, gurih, disajikan dengan sayur sawi dan telur mata sapi lembut.',
                'buy_price' => 9000,
                'sell_price' => 13000,
                'stock' => 15,
                'min_stock' => 5,
                'image' => 'images/menu/mie-kuah.png',
                'is_available' => true,
                'is_favorite' => false,
            ],
            [
                'category_id' => $catMinuman->id,
                'sku' => 'WMW-005',
                'name' => 'Es Teh Manis',
                'slug' => 'es-teh-manis',
                'description' => 'Teh melati wangi dengan seduhan gula asli dingin menyegarkan peneman santap favorit.',
                'buy_price' => 2000,
                'sell_price' => 5000,
                'stock' => 50,
                'min_stock' => 10,
                'image' => 'images/menu/es-teh-manis.png',
                'is_available' => true,
                'is_favorite' => true,
            ],
            [
                'category_id' => $catMinuman->id,
                'sku' => 'WMW-006',
                'name' => 'Es Jeruk',
                'slug' => 'es-jeruk',
                'description' => 'Perasan jeruk asli manis alami dengan sensasi bulir segar dan es batu dingin.',
                'buy_price' => 3000,
                'sell_price' => 7000,
                'stock' => 30,
                'min_stock' => 10,
                'image' => 'images/menu/es-jeruk.png',
                'is_available' => true,
                'is_favorite' => false,
            ],
            [
                'category_id' => $catMinuman->id,
                'sku' => 'WMW-007',
                'name' => 'Es Milo',
                'slug' => 'es-milo',
                'description' => 'Minuman cokelat malt pekat legit creamy dengan taburan bubuk milo di atasnya.',
                'buy_price' => 4000,
                'sell_price' => 8000,
                'stock' => 22,
                'min_stock' => 8,
                'image' => 'images/menu/es-milo.png',
                'is_available' => true,
                'is_favorite' => false,
            ],
            [
                'category_id' => $catSnack->id,
                'sku' => 'WMW-008',
                'name' => 'Kentang Goreng',
                'slug' => 'kentang-goreng',
                'description' => 'Kentang renyah keemasan dibumbui taburan gurih istimewa, disajikan dengan saus sambal.',
                'buy_price' => 5000,
                'sell_price' => 10000,
                'stock' => 15,
                'min_stock' => 5,
                'image' => 'images/menu/kentang-goreng.png',
                'is_available' => true,
                'is_favorite' => true,
            ],
            [
                'category_id' => $catNasi->id,
                'sku' => 'WMW-009',
                'name' => 'Nasi Goreng',
                'slug' => 'nasi-goreng',
                'description' => 'Nasi goreng racikan bumbu khas Warmindo wangi asap lezat, lengkap dengan acar, kerupuk, dan telur.',
                'buy_price' => 11000,
                'sell_price' => 16000,
                'stock' => 12,
                'min_stock' => 4,
                'image' => 'images/menu/nasi-goreng.png',
                'is_available' => true,
                'is_favorite' => false,
            ],
            [
                'category_id' => $catSnack->id,
                'sku' => 'WMW-010',
                'name' => 'Kerupuk',
                'slug' => 'kerupuk',
                'description' => 'Kerupuk kaleng renyah gurih pelengkap wajib makan mie dan nasi.',
                'buy_price' => 1500,
                'sell_price' => 3000,
                'stock' => 40,
                'min_stock' => 10,
                'image' => 'images/menu/kerupuk.png',
                'is_available' => true,
                'is_favorite' => false,
            ],
            // Low stock demo items for alert display
            [
                'category_id' => $catSnack->id,
                'sku' => 'WMW-011',
                'name' => 'Telur Rebus',
                'slug' => 'telur-rebus',
                'description' => 'Tambahan telur rebus matang lembut berprotein tinggi.',
                'buy_price' => 2500,
                'sell_price' => 4000,
                'stock' => 3,
                'min_stock' => 10,
                'image' => 'images/menu/telur-rebus.png',
                'is_available' => true,
                'is_favorite' => false,
            ],
            [
                'category_id' => $catNasi->id,
                'sku' => 'WMW-012',
                'name' => 'Ayam Goreng',
                'slug' => 'ayam-goreng',
                'description' => 'Ayam goreng gurih bumbu rempah kuning gurih garing.',
                'buy_price' => 10000,
                'sell_price' => 15000,
                'stock' => 4,
                'min_stock' => 10,
                'image' => 'images/menu/ayam-goreng.png',
                'is_available' => true,
                'is_favorite' => false,
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $data) {
            $createdProducts[$data['sku']] = Product::create($data);
        }

        // 4. Suppliers
        $supAbc = Supplier::create([
            'name' => 'PT ABC Food',
            'contact_person' => 'Budi Hartono',
            'phone' => '0811-234-1800',
            'email' => 'sales@abcfood.com',
            'address' => 'Kawasan Industri Pulo Gadung, Jakarta Timur',
            'is_active' => true,
        ]);

        $supMaju = Supplier::create([
            'name' => 'CV Maju Jaya',
            'contact_person' => 'Hendra Wijaya',
            'phone' => '0812-987-6543',
            'email' => 'hendra@majujaya.com',
            'address' => 'Jl. Pasar Minggu No. 45, Jakarta Selatan',
            'is_active' => true,
        ]);

        $supSumber = Supplier::create([
            'name' => 'Sumber Rezeki',
            'contact_person' => 'Dewi Lestari',
            'phone' => '0813-555-8888',
            'email' => 'order@sumberrezeki.com',
            'address' => 'Jl. Surya Kencana No. 12, Bogor',
            'is_active' => true,
        ]);

        $supIndo = Supplier::create([
            'name' => 'Indo Prima',
            'contact_person' => 'Agus Setiawan',
            'phone' => '0818-091-2345',
            'email' => 'admin@indoprima.co.id',
            'address' => 'Jl. Daan Mogot KM 11, Jakarta Barat',
            'is_active' => true,
        ]);

        // 5. Purchases
        $p1 = Purchase::create([
            'purchase_number' => 'PB-001',
            'supplier_id' => $supAbc->id,
            'user_id' => $admin->id,
            'purchase_date' => Carbon::now()->subDays(10),
            'total_amount' => 450000,
            'status' => 'completed',
            'notes' => 'Pasokan rutin mie dan bumbu',
        ]);
        PurchaseItem::create([
            'purchase_id' => $p1->id,
            'product_id' => $createdProducts['WMW-001']->id,
            'quantity' => 30,
            'unit_price' => 8000,
            'subtotal' => 240000,
        ]);
        PurchaseItem::create([
            'purchase_id' => $p1->id,
            'product_id' => $createdProducts['WMW-004']->id,
            'quantity' => 20,
            'unit_price' => 9000,
            'subtotal' => 180000,
        ]);

        $p2 = Purchase::create([
            'purchase_number' => 'PB-002',
            'supplier_id' => $supAbc->id,
            'user_id' => $admin->id,
            'purchase_date' => Carbon::now()->subDays(5),
            'total_amount' => 326000,
            'status' => 'completed',
            'notes' => 'Stok tambahan teh dan jeruk',
        ]);
        PurchaseItem::create([
            'purchase_id' => $p2->id,
            'product_id' => $createdProducts['WMW-005']->id,
            'quantity' => 50,
            'unit_price' => 2000,
            'subtotal' => 100000,
        ]);
        PurchaseItem::create([
            'purchase_id' => $p2->id,
            'product_id' => $createdProducts['WMW-006']->id,
            'quantity' => 30,
            'unit_price' => 3000,
            'subtotal' => 90000,
        ]);

        // Record Initial Stock Mutations for Auditing
        StockMutation::create([
            'product_id' => $createdProducts['WMW-001']->id,
            'type' => 'in',
            'reference_type' => 'purchase',
            'reference_id' => $p1->id,
            'reference_number' => 'PB-001',
            'quantity' => 30,
            'before_stock' => 0,
            'after_stock' => 30,
            'notes' => 'Pembelian dari PT ABC Food',
            'user_id' => $admin->id,
            'created_at' => Carbon::now()->subDays(10),
        ]);

        // 6. Sales matching Kasir and Kitchen UI Screenshots
        // #PSN-0012: Waiting Payment (Dine In Meja 3)
        $s12 = Sale::create([
            'sale_number' => 'PSN-0012',
            'user_id' => null,
            'customer_name' => 'Pelanggan Meja 3',
            'order_type' => 'dine_in',
            'table_number' => '3',
            'subtotal' => 40000,
            'service_fee' => 0,
            'total_amount' => 40000,
            'payment_status' => 'pending',
            'order_status' => 'waiting_payment',
            'notes' => 'Mie jangan terlalu lembek ya mas',
            'created_at' => Carbon::now()->subMinutes(15),
        ]);
        SaleItem::create([
            'sale_id' => $s12->id,
            'product_id' => $createdProducts['WMW-002']->id,
            'product_name' => 'Mie Ayam',
            'price' => 15000,
            'quantity' => 2,
            'subtotal' => 30000,
            'notes' => 'Satu pedas, satu biasa',
        ]);
        SaleItem::create([
            'sale_id' => $s12->id,
            'product_id' => $createdProducts['WMW-005']->id,
            'product_name' => 'Es Teh Manis',
            'price' => 5000,
            'quantity' => 1,
            'subtotal' => 5000,
            'notes' => 'Es dipisah',
        ]);
        SaleItem::create([
            'sale_id' => $s12->id,
            'product_id' => $createdProducts['WMW-010']->id,
            'product_name' => 'Kerupuk',
            'price' => 3000,
            'quantity' => 1,
            'subtotal' => 3000,
            'notes' => null,
        ]);

        // #PSN-0011: Waiting Payment (Dine In Meja 5)
        $s11 = Sale::create([
            'sale_number' => 'PSN-0011',
            'user_id' => null,
            'customer_name' => 'Pelanggan Meja 5',
            'order_type' => 'dine_in',
            'table_number' => '5',
            'subtotal' => 5000,
            'service_fee' => 0,
            'total_amount' => 5000,
            'payment_status' => 'pending',
            'order_status' => 'waiting_payment',
            'created_at' => Carbon::now()->subMinutes(20),
        ]);
        SaleItem::create([
            'sale_id' => $s11->id,
            'product_id' => $createdProducts['WMW-005']->id,
            'product_name' => 'Es Teh Manis',
            'price' => 5000,
            'quantity' => 1,
            'subtotal' => 5000,
        ]);

        // #PSN-0010: Waiting Payment (Dine In Meja 1)
        $s10 = Sale::create([
            'sale_number' => 'PSN-0010',
            'user_id' => null,
            'customer_name' => 'Pelanggan Meja 1',
            'order_type' => 'dine_in',
            'table_number' => '1',
            'subtotal' => 3000,
            'service_fee' => 0,
            'total_amount' => 3000,
            'payment_status' => 'pending',
            'order_status' => 'waiting_payment',
            'created_at' => Carbon::now()->subMinutes(25),
        ]);
        SaleItem::create([
            'sale_id' => $s10->id,
            'product_id' => $createdProducts['WMW-010']->id,
            'product_name' => 'Kerupuk',
            'price' => 3000,
            'quantity' => 1,
            'subtotal' => 3000,
        ]);

        // #PSN-0128: Paid & Queued in Kitchen (Dine In Meja 3)
        $s128 = Sale::create([
            'sale_number' => 'PSN-0128',
            'user_id' => $kasir->id,
            'customer_name' => 'Pelanggan Meja 3',
            'order_type' => 'dine_in',
            'table_number' => '3',
            'subtotal' => 17000,
            'service_fee' => 0,
            'total_amount' => 17000,
            'payment_status' => 'paid',
            'order_status' => 'queued',
            'payment_method' => 'cash',
            'cash_received' => 20000,
            'cash_change' => 3000,
            'paid_at' => Carbon::now()->subMinutes(10),
            'notes' => 'Indomie setengah matang',
            'created_at' => Carbon::now()->subMinutes(12),
        ]);
        SaleItem::create([
            'sale_id' => $s128->id,
            'product_id' => $createdProducts['WMW-001']->id,
            'product_name' => 'Indomie Goreng',
            'price' => 12000,
            'quantity' => 1,
            'subtotal' => 12000,
            'notes' => 'Setengah matang',
            'status' => 'queued',
        ]);
        SaleItem::create([
            'sale_id' => $s128->id,
            'product_id' => $createdProducts['WMW-005']->id,
            'product_name' => 'Es Teh Manis',
            'price' => 5000,
            'quantity' => 1,
            'subtotal' => 5000,
            'notes' => 'Manis sedang',
            'status' => 'queued',
        ]);
        OrderStatusHistory::create([
            'sale_id' => $s128->id,
            'from_status' => 'waiting_payment',
            'to_status' => 'queued',
            'staff_name' => 'Kasir: Siti Aisyah',
            'user_id' => $kasir->id,
            'notes' => 'Pembayaran tunai lunas Rp 20.000',
            'created_at' => Carbon::now()->subMinutes(10),
        ]);

        // #PSN-0127: Preparing (Dine In Meja 5)
        $s127 = Sale::create([
            'sale_number' => 'PSN-0127',
            'user_id' => $kasir->id,
            'customer_name' => 'Pelanggan Meja 5',
            'order_type' => 'dine_in',
            'table_number' => '5',
            'subtotal' => 22000,
            'service_fee' => 0,
            'total_amount' => 22000,
            'payment_status' => 'paid',
            'order_status' => 'preparing',
            'payment_method' => 'qris',
            'paid_at' => Carbon::now()->subMinutes(15),
            'created_at' => Carbon::now()->subMinutes(20),
        ]);
        SaleItem::create([
            'sale_id' => $s127->id,
            'product_id' => $createdProducts['WMW-002']->id,
            'product_name' => 'Mie Ayam',
            'price' => 15000,
            'quantity' => 1,
            'subtotal' => 15000,
            'status' => 'preparing',
        ]);
        SaleItem::create([
            'sale_id' => $s127->id,
            'product_id' => $createdProducts['WMW-006']->id,
            'product_name' => 'Es Jeruk',
            'price' => 7000,
            'quantity' => 1,
            'subtotal' => 7000,
            'status' => 'preparing',
        ]);
        OrderStatusHistory::create([
            'sale_id' => $s127->id,
            'from_status' => 'queued',
            'to_status' => 'preparing',
            'staff_name' => 'Dapur: Budi',
            'user_id' => $budi->id,
            'notes' => 'Mulai dimasak',
            'created_at' => Carbon::now()->subMinutes(12),
        ]);

        // #PSN-0126: Ready to Serve (Dine In Meja 7)
        $s126 = Sale::create([
            'sale_number' => 'PSN-0126',
            'user_id' => $kasir->id,
            'customer_name' => 'Pelanggan Meja 7',
            'order_type' => 'dine_in',
            'table_number' => '7',
            'subtotal' => 18000,
            'service_fee' => 0,
            'total_amount' => 18000,
            'payment_status' => 'paid',
            'order_status' => 'ready_to_serve',
            'payment_method' => 'cash',
            'cash_received' => 20000,
            'cash_change' => 2000,
            'paid_at' => Carbon::now()->subMinutes(30),
            'created_at' => Carbon::now()->subMinutes(35),
        ]);
        SaleItem::create([
            'sale_id' => $s126->id,
            'product_id' => $createdProducts['WMW-004']->id,
            'product_name' => 'Mie Kuah',
            'price' => 13000,
            'quantity' => 1,
            'subtotal' => 13000,
            'status' => 'ready_to_serve',
        ]);
        SaleItem::create([
            'sale_id' => $s126->id,
            'product_id' => $createdProducts['WMW-005']->id,
            'product_name' => 'Es Teh Manis',
            'price' => 5000,
            'quantity' => 1,
            'subtotal' => 5000,
            'status' => 'ready_to_serve',
        ]);

        // #PSN-0125: Ready to Serve (Take Away)
        $s125 = Sale::create([
            'sale_number' => 'PSN-0125',
            'user_id' => $kasir->id,
            'customer_name' => 'Take Away',
            'order_type' => 'take_away',
            'table_number' => null,
            'subtotal' => 24000,
            'service_fee' => 0,
            'total_amount' => 24000,
            'payment_status' => 'paid',
            'order_status' => 'ready_to_serve',
            'payment_method' => 'cash',
            'cash_received' => 50000,
            'cash_change' => 26000,
            'paid_at' => Carbon::now()->subMinutes(45),
            'created_at' => Carbon::now()->subMinutes(50),
        ]);
        SaleItem::create([
            'sale_id' => $s125->id,
            'product_id' => $createdProducts['WMW-009']->id,
            'product_name' => 'Nasi Goreng',
            'price' => 16000,
            'quantity' => 1,
            'subtotal' => 16000,
            'status' => 'ready_to_serve',
        ]);
        SaleItem::create([
            'sale_id' => $s125->id,
            'product_id' => $createdProducts['WMW-007']->id,
            'product_name' => 'Es Milo',
            'price' => 8000,
            'quantity' => 1,
            'subtotal' => 8000,
            'status' => 'ready_to_serve',
        ]);

        // #PSN-0124: Completed (Dine In Meja 2)
        $s124 = Sale::create([
            'sale_number' => 'PSN-0124',
            'user_id' => $kasir->id,
            'customer_name' => 'Pelanggan Meja 2',
            'order_type' => 'dine_in',
            'table_number' => '2',
            'subtotal' => 19000,
            'service_fee' => 0,
            'total_amount' => 19000,
            'payment_status' => 'paid',
            'order_status' => 'completed',
            'payment_method' => 'cash',
            'cash_received' => 20000,
            'cash_change' => 1000,
            'paid_at' => Carbon::now()->subMinutes(60),
            'created_at' => Carbon::now()->subMinutes(70),
        ]);
        SaleItem::create([
            'sale_id' => $s124->id,
            'product_id' => $createdProducts['WMW-001']->id,
            'product_name' => 'Indomie Goreng',
            'price' => 12000,
            'quantity' => 1,
            'subtotal' => 12000,
            'status' => 'completed',
        ]);
        SaleItem::create([
            'sale_id' => $s124->id,
            'product_id' => $createdProducts['WMW-006']->id,
            'product_name' => 'Es Jeruk',
            'price' => 7000,
            'quantity' => 1,
            'subtotal' => 7000,
            'status' => 'completed',
        ]);

        // Historical Sales for the past 7 days to give vibrant realistic charts
        for ($i = 1; $i <= 7; $i++) {
            $date = Carbon::now()->subDays($i)->setTime(rand(10, 21), rand(0, 59));
            $dailyCount = rand(5, 8);
            for ($k = 1; $k <= $dailyCount; $k++) {
                $isDineIn = (rand(1, 10) <= 7);
                $payMethod = (rand(1, 10) <= 6) ? 'cash' : 'qris';
                $orderTotal = rand(2, 4) * 12000;
                $historySale = Sale::create([
                    'sale_number' => 'TRX-' . $date->format('md') . '-' . str_pad($k, 3, '0', STR_PAD_LEFT),
                    'user_id' => $kasir->id,
                    'customer_name' => $isDineIn ? 'Meja ' . rand(1, 10) : 'Take Away',
                    'order_type' => $isDineIn ? 'dine_in' : 'take_away',
                    'table_number' => $isDineIn ? (string)rand(1, 10) : null,
                    'subtotal' => $orderTotal,
                    'service_fee' => 0,
                    'total_amount' => $orderTotal,
                    'payment_status' => 'paid',
                    'order_status' => 'completed',
                    'payment_method' => $payMethod,
                    'cash_received' => $orderTotal + 5000,
                    'cash_change' => 5000,
                    'paid_at' => $date,
                    'created_at' => $date,
                ]);

                SaleItem::create([
                    'sale_id' => $historySale->id,
                    'product_id' => $createdProducts['WMW-001']->id,
                    'product_name' => 'Indomie Goreng',
                    'price' => 12000,
                    'quantity' => 1,
                    'subtotal' => 12000,
                    'status' => 'completed',
                    'created_at' => $date,
                ]);
                SaleItem::create([
                    'sale_id' => $historySale->id,
                    'product_id' => $createdProducts['WMW-005']->id,
                    'product_name' => 'Es Teh Manis',
                    'price' => 5000,
                    'quantity' => 1,
                    'subtotal' => 5000,
                    'status' => 'completed',
                    'created_at' => $date,
                ]);
            }
        }
    }
}
