<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ongkir_zonas', function (Blueprint $table) {
            $table->id();
            $table->string('kota');
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->default('Jawa Timur');
            $table->decimal('jarak_km', 8, 2)->nullable();
            $table->enum('zona', ['1', '2', '3', '4', '5'])->default('1');
            $table->decimal('tarif_pickup', 15, 2)->default(0);
            $table->decimal('tarif_engkel', 15, 2)->default(0);
            $table->decimal('tarif_truk', 15, 2)->default(0);
            $table->boolean('tersedia_armada')->default(true);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ongkir_zonas');
    }
};