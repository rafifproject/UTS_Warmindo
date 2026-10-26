<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id()->comment('Nomor ID unik rincian item pembelian');
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade')->comment('ID relasi ke nota induk di tabel purchases');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict')->comment('ID relasi ke tabel products (barang yang dibeli)');
            $table->integer('quantity')->comment('Jumlah kuantitas barang yang dibeli (menambah stok produk)');
            $table->decimal('unit_price', 12, 2)->comment('Harga beli satuan per barang saat transaksi (Rp)');
            $table->decimal('subtotal', 12, 2)->comment('Subtotal biaya per item (quantity * unit_price)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
