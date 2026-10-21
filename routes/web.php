<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SupplierController as AdminSupplierController;
use App\Http\Controllers\Admin\PurchaseController as AdminPurchaseController;
use App\Http\Controllers\Admin\SaleController as AdminSaleController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\AiAssistantController as AdminAiAssistantController;

/*
|--------------------------------------------------------------------------
| Public Customer Routes (Tahap 3 - Screens 50-56)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/menu', [PublicController::class, 'menu'])->name('menu');
Route::get('/menu/{slug}', [PublicController::class, 'menuDetail'])->name('menu.detail');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{id}', [CartController::class, 'add'])->name('add');
    Route::post('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::post('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::get('/service', [CartController::class, 'service'])->name('service');
    Route::post('/service', [CartController::class, 'saveService'])->name('service.save');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CartController::class, 'storeOrder'])->name('checkout.store');
    Route::get('/success/{saleNumber}', [CartController::class, 'success'])->name('success');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Screen A)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');

/*
|--------------------------------------------------------------------------
| Staff / Kasir Routes (Tahap 2 - Screens 42-49)
| Accessible by role: staff, admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff,admin'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/', [KasirController::class, 'dashboard'])->name('dashboard');
    Route::get('/waiting', [KasirController::class, 'waitingList'])->name('waiting');
    Route::get('/search', [KasirController::class, 'searchOrders'])->name('search');
    Route::get('/order/{saleNumber}', [KasirController::class, 'detail'])->name('detail');
    Route::get('/pay/{saleNumber}', [KasirController::class, 'paymentForm'])->name('pay');
    Route::post('/pay/{saleNumber}', [KasirController::class, 'processPayment'])->name('pay.post');
    Route::get('/receipt/{saleNumber}', [KasirController::class, 'receipt'])->name('receipt');
});

/*
|--------------------------------------------------------------------------
| Dapur / Kitchen Display Routes (Tahap 4 - Screens 57-60)
| Accessible by authenticated staff or admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('dapur')->name('kitchen.')->group(function () {
    Route::get('/', [KitchenController::class, 'dashboard'])->name('dashboard');
    Route::get('/order/{saleNumber}', [KitchenController::class, 'detail'])->name('detail');
    Route::post('/order/{saleNumber}/status', [KitchenController::class, 'updateStatus'])->name('status.update');
    Route::get('/history', [KitchenController::class, 'history'])->name('history');
});

/*
|--------------------------------------------------------------------------
| Admin / Owner Routes (Screens A-I)
| Protected strictly with role: admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management (Screen B)
    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Category CRUD (Screen C)
    Route::resource('categories', AdminCategoryController::class);

    // Product & Inventory CRUD (Screen D)
    Route::get('products/low-stock', [AdminProductController::class, 'lowStock'])->name('products.low-stock');
    Route::get('products/stock-mutations', [AdminProductController::class, 'stockMutations'])->name('products.stock-mutations');
    Route::resource('products', AdminProductController::class);

    // Suppliers (Screen E)
    Route::resource('suppliers', AdminSupplierController::class);

    // Purchases / Stok Masuk (Screen F)
    Route::resource('purchases', AdminPurchaseController::class);

    // Sales & Transactions (Screen G)
    Route::get('sales', [AdminSaleController::class, 'index'])->name('sales.index');
    Route::get('sales/{sale}', [AdminSaleController::class, 'show'])->name('sales.show');
    Route::post('sales/{sale}/cancel', [AdminSaleController::class, 'cancel'])->name('sales.cancel');

    // 7 Interactive Reports with Chart & Table Toggle (Screen H)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminReportController::class, 'index'])->name('index');
        Route::get('/sales', [AdminReportController::class, 'sales'])->name('sales');
        Route::get('/transactions', [AdminReportController::class, 'transactions'])->name('transactions');
        Route::get('/top-menus', [AdminReportController::class, 'topMenus'])->name('top-menus');
        Route::get('/stock', [AdminReportController::class, 'stock'])->name('stock');
        Route::get('/purchases', [AdminReportController::class, 'purchases'])->name('purchases');
        Route::get('/dine-in-vs-take-away', [AdminReportController::class, 'dineInVsTakeAway'])->name('dine-in-vs-take-away');
        Route::get('/payments', [AdminReportController::class, 'payments'])->name('payments');
        Route::get('/export/{type}', [AdminReportController::class, 'exportCsv'])->name('export');
    });

    // AI Business Assistant (Screen I)
    Route::get('ai-assistant', [AdminAiAssistantController::class, 'index'])->name('ai.index');
    Route::post('ai-assistant/analyze', [AdminAiAssistantController::class, 'analyze'])->name('ai.analyze');
});
