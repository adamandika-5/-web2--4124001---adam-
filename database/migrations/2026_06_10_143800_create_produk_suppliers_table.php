<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('produk_suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produks')->cascadeOnDelete();
            $table->decimal('harga_beli', 15, 2);
            $table->string('satuan', 30)->default('pcs');
            $table->unsignedInteger('minimal_pembelian')->default(1);
            $table->unsignedSmallInteger('lead_time_hari')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->unique(['supplier_id', 'produk_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_suppliers');
    }
};
