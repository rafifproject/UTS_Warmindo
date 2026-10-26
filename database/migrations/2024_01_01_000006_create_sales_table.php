<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id()->comment('Nomor ID unik transaksi penjualan');
            $table->string('sale_number')->unique()->comment('Nomor unik struk penjualan pelanggan (contoh: PSN-0001)');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('ID staf kasir yang memproses pembayaran');
            $table->string('customer_name')->default('Pelanggan')->comment('Nama pemesan / identitas meja pelanggan');
            $table->enum('order_type', ['dine_in', 'take_away'])->comment('Tipe pesanan: dine_in (makan di tempat) atau take_away (bungkus/bawa pulang)');
            $table->string('table_number')->nullable()->comment('Nomor meja pelanggan (wajib jika dine_in, NULL jika take_away)');
            $table->decimal('subtotal', 12, 2)->default(0)->comment('Subtotal harga seluruh item pesanan (Rp)');
            $table->decimal('service_fee', 12, 2)->default(0)->comment('Biaya layanan / tambahan (Rp)');
            $table->decimal('total_amount', 12, 2)->default(0)->comment('Total tagihan akhir yang harus dibayar pelanggan (Rp)');
            $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('pending')->comment('Status pembayaran: pending (belum bayar), paid (lunas), cancelled (batal)');
            $table->enum('order_status', ['waiting_payment', 'queued', 'preparing', 'ready_to_serve', 'completed', 'cancelled'])->default('waiting_payment')->comment('Tahapan masakan di dapur: waiting_payment, queued, preparing, ready_to_serve, completed, cancelled');
            $table->string('payment_method')->nullable()->comment('Metode pembayaran: cash (tunai), qris, atau transfer');
            $table->decimal('cash_received', 12, 2)->nullable()->comment('Nominal uang tunai yang diterima dari pelanggan (Rp)');
            $table->decimal('cash_change', 12, 2)->nullable()->comment('Nominal uang kembalian pelanggan (Rp)');
            $table->timestamp('paid_at')->nullable()->comment('Waktu pelunasan transaksi di kasir');
            $table->text('notes')->nullable()->comment('Catatan pesanan dari pelanggan atau alasan pembatalan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
