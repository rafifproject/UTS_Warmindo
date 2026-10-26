<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_mutations', function (Blueprint $table) {
            $table->id()->comment('Nomor ID unik riwayat mutasi stok');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->comment('ID produk yang mengalami pergerakan stok');
            $table->enum('type', ['in', 'out', 'adjustment'])->comment('Jenis pergerakan: in (stok masuk), out (stok keluar), adjustment (penyesuaian/restorasi)');
            $table->string('reference_type')->nullable()->comment('Jenis dokumen referensi: purchase (pembelian), sale (penjualan), adjustment');
            $table->unsignedBigInteger('reference_id')->nullable()->comment('ID baris dokumen referensi terkait');
            $table->string('reference_number')->nullable()->comment('Nomor nota referensi (contoh: PB-001, PSN-0001)');
            $table->integer('quantity')->comment('Besaran jumlah kuantitas yang bermutasi');
            $table->integer('before_stock')->comment('Jumlah stok fisik sebelum mutasi terjadi');
            $table->integer('after_stock')->comment('Jumlah stok fisik setelah mutasi selesai');
            $table->string('notes')->nullable()->comment('Keterangan audit riwayat perubahan stok');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('ID petugas yang melakukan aksi transaksi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_mutations');
    }
};
