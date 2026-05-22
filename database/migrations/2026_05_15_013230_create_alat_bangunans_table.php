<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alat_bangunans', function (Blueprint $table) {
            $table->id();

            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('kategori_alat', 80)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();

            $table->decimal('tarif_harian', 15, 2);
            $table->decimal('tarif_mingguan', 15, 2)->nullable();
            $table->decimal('tarif_bulanan', 15, 2)->nullable();
            $table->decimal('deposit', 15, 2)->default(0);
            $table->decimal('denda_per_hari', 15, 2)->default(0);

            $table->integer('jumlah_unit')->default(1);
            $table->integer('tersedia')->default(1);

            $table->enum('kondisi', ['baik', 'cukup', 'perbaikan'])->default('baik');
            $table->boolean('aktif')->default(true);
            $table->text('catatan')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alat_bangunans');
    }
};