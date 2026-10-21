<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->string('reference_type')->nullable(); // purchase, sale, adjustment
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_number')->nullable(); // PB-001, PSN-0012
            $table->integer('quantity');
            $table->integer('before_stock');
            $table->integer('after_stock');
            $table->string('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_mutations');
    }
};
