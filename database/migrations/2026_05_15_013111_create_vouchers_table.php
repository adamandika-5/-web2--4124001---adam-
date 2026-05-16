<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique();
            $table->string('nama');
            $table->enum('tipe', ['persentase','nominal','gratis_ongkir'])->default('nominal');
            $table->decimal('nilai', 10, 2);
            $table->decimal('min_belanja', 15, 2)->default(0);
            $table->integer('kuota')->nullable();
            $table->integer('terpakai')->default(0);
            $table->integer('maks_per_user')->default(1);
            $table->boolean('aktif')->default(true);
            $table->timestamp('berlaku_mulai')->nullable();
            $table->timestamp('berlaku_sampai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('vouchers');
    }
};