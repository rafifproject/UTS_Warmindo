<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id()->comment('Nomor ID unik kategori');
            $table->string('name')->comment('Nama kategori menu (contoh: Mie, Minuman, Snack, Nasi)');
            $table->string('slug')->unique()->comment('Slug URL unik kategori (contoh: mie, minuman)');
            $table->text('description')->nullable()->comment('Deskripsi penjelasan kategori');
            $table->boolean('is_active')->default(true)->comment('Status aktif kategori: 1 (aktif), 0 (nonaktif)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
