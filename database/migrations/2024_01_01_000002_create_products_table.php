<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id()->comment('Nomor ID unik produk menu');
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict')->comment('ID relasi ke tabel categories (kategori menu)');
            $table->string('sku')->unique()->comment('Kode unik SKU barang (Stock Keeping Unit)');
            $table->string('name')->comment('Nama produk menu Warmindo');
            $table->string('slug')->unique()->comment('Slug URL unik produk menu');
            $table->text('description')->nullable()->comment('Deskripsi lengkap sajian menu');
            $table->decimal('buy_price', 12, 2)->default(0)->comment('Harga beli / modal bahan per porsi dari supplier (Rp)');
            $table->decimal('sell_price', 12, 2)->comment('Harga jual ke pelanggan per porsi (Rp)');
            $table->integer('stock')->default(0)->comment('Jumlah sisa stok fisik siap jual saat ini');
            $table->integer('min_stock')->default(5)->comment('Batas ambang minimum stok untuk memicu peringatan menipis');
            $table->string('image')->nullable()->comment('Path lokasi file foto produk di public/images/menu/');
            $table->boolean('is_available')->default(true)->comment('Status ketersediaan menu: 1 (tersedia), 0 (habis/nonaktif)');
            $table->boolean('is_favorite')->default(false)->comment('Penanda menu favorit untuk tampil di beranda: 1 (favorit), 0 (biasa)');
            $table->timestamps();
            $table->softDeletes()->comment('Waktu soft delete (penghapusan aman tanpa merusak riwayat transaksi)');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
