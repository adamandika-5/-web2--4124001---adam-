<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained()->restrictOnDelete();
            $table->foreignId('sub_kategori_id')->nullable()->constrained('sub_kategoris')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();

            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('sku', 80)->unique()->nullable();

            $table->text('deskripsi');
            $table->text('spesifikasi')->nullable();

            $table->decimal('harga', 15, 2);
            $table->decimal('harga_promo', 15, 2)->nullable();

            $table->integer('stok')->default(0);
            $table->string('satuan', 30);

            $table->boolean('aktif')->default(true);
            $table->boolean('unggulan')->default(false);
            $table->integer('terjual')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('produks');
    }
};