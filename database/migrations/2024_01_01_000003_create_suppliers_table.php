<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id()->comment('Nomor ID unik pemasok / supplier');
            $table->string('name')->comment('Nama perusahaan atau nama pemasok bahan baku');
            $table->string('contact_person')->nullable()->comment('Nama kontak penanggung jawab / sales pemasok');
            $table->string('phone')->nullable()->comment('Nomor telepon / WhatsApp pemasok');
            $table->string('email')->nullable()->comment('Alamat email pemasok');
            $table->text('address')->nullable()->comment('Alamat fisik kantor / gudang pemasok');
            $table->boolean('is_active')->default(true)->comment('Status rekanan aktif: 1 (aktif), 0 (tidak aktif)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
