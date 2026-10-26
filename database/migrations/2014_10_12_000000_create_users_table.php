<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('Nomor ID unik pengguna');
            $table->string('name')->comment('Nama lengkap pengguna / staf / owner');
            $table->string('username')->unique()->nullable()->comment('Username unik untuk login sistem');
            $table->string('email')->unique()->comment('Alamat email unik pengguna');
            $table->timestamp('email_verified_at')->nullable()->comment('Waktu verifikasi email');
            $table->string('password')->comment('Kata sandi terenkripsi (bcrypt)');
            $table->enum('role', ['admin', 'staff'])->default('staff')->comment('Peran hak akses: admin (Owner) atau staff (Kasir & Dapur)');
            $table->string('phone')->nullable()->comment('Nomor telepon atau WhatsApp');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Status akun: active (aktif) atau inactive (nonaktif)');
            $table->string('avatar')->nullable()->comment('File foto profil pengguna');
            $table->rememberToken()->comment('Token untuk sesi login tetap aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
