<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pesanan')->unique();

            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();

            $table->string('penerima');
            $table->string('telepon_penerima', 20);
            $table->text('alamat_pengiriman');

            $table->string('kota_tujuan');
            $table->string('provinsi_tujuan');

            $table->decimal('subtotal', 15, 2);
            $table->decimal('total', 15, 2);

            $table->enum('status', ['pending','diproses','dikirim','selesai','batal'])->default('pending');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pesanans');
    }
};