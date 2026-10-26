<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id()->comment('Nomor ID unik transaksi pembelian');
            $table->string('purchase_number')->unique()->comment('Nomor nota unik pembelian / PO (contoh: PB-001)');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict')->comment('ID relasi ke tabel suppliers (pemasok barang)');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('ID staf/owner yang menginput pembelian');
            $table->date('purchase_date')->comment('Tanggal dilakukannya transaksi pembelian');
            $table->decimal('total_amount', 12, 2)->default(0)->comment('Total nominal rupiah keseluruhan pembelian');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed')->comment('Status pembelian: completed (selesai/stok masuk), pending, cancelled');
            $table->text('notes')->nullable()->comment('Catatan tambahan transaksi pembelian');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
