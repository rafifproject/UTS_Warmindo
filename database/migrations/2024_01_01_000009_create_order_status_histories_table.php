<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id()->comment('Nomor ID unik log status dapur');
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade')->comment('ID pesanan terkait di tabel sales');
            $table->string('from_status')->nullable()->comment('Status masakan dapur sebelumnya');
            $table->string('to_status')->comment('Status masakan dapur yang baru');
            $table->string('staff_name')->default('Sistem / Dapur')->comment('Nama petugas dapur yang memajukan tahapan');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('ID user staf yang login saat aksi');
            $table->string('notes')->nullable()->comment('Catatan tambahan pengerjaan dapur');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
    }
};
