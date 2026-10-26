<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id()->comment('Nomor ID unik rincian item pesanan pelanggan');
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade')->comment('ID relasi ke nota transaksi induk di tabel sales');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict')->comment('ID relasi ke menu di tabel products');
            $table->string('product_name')->comment('Nama menu saat transaksi berlangsung');
            $table->decimal('price', 12, 2)->comment('Harga jual satuan resmi saat transaksi (Rp)');
            $table->integer('quantity')->comment('Jumlah porsi yang dipesan (memotong stok produk)');
            $table->decimal('subtotal', 12, 2)->comment('Subtotal harga item (quantity * price)');
            $table->text('notes')->nullable()->comment('Catatan khusus pelanggan untuk menu ini (contoh: ekstra pedas)');
            $table->enum('status', ['pending', 'queued', 'preparing', 'ready_to_serve', 'completed'])->default('pending')->comment('Status pengerjaan item di dapur');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
