<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alamat_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label', 50);
            $table->string('penerima');
            $table->string('telepon', 20);
            $table->text('alamat_lengkap');
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota');
            $table->string('provinsi');
            $table->string('kode_pos', 10)->nullable();
            $table->boolean('is_utama')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('alamat_users');
    }
};