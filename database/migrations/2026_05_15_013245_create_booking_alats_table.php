<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_alats', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_booking', 30)->unique();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('alat_id')->constrained('alat_bangunans')->restrictOnDelete();

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->date('tanggal_kembali_aktual')->nullable();

            $table->integer('durasi_hari');
            $table->decimal('tarif_per_hari', 15, 2);
            $table->decimal('total_sewa', 15, 2);
            $table->decimal('deposit', 15, 2)->default(0);
            $table->decimal('denda', 15, 2)->default(0);
            $table->integer('hari_terlambat')->default(0);
            $table->decimal('total_bayar', 15, 2);

            $table->enum('status', ['pending', 'aktif', 'selesai', 'batal'])->default('pending');
            $table->text('alamat_penggunaan')->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['alat_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_alats');
    }
};