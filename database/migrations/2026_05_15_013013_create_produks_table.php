<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kategori_id')
                ->nullable()
                ->constrained('kategoris')
                ->nullOnDelete();

            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained('suppliers')
                ->nullOnDelete();

            $table->string('sku')->unique();
            $table->string('nama');
            $table->string('slug')->unique();

            $table->decimal('harga', 15, 2)->default(0);
            $table->decimal('harga_promo', 15, 2)->nullable();

            $table->integer('stok')->default(0);
            $table->string('satuan', 30)->default('pcs');

            $table->decimal('berat', 10, 2)->default(0);
            $table->string('jenis_pengiriman', 30)->default('armada');

            $table->boolean('unggulan')->default(false);
            $table->string('ikon', 20)->nullable();
            $table->string('warna_bg', 30)->nullable();

            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();

            $table->integer('terjual')->default(0);
            $table->boolean('aktif')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};