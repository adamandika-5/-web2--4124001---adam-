<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('promoes', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->enum('tipe', ['persentase','nominal','gratis_ongkir'])->default('persentase');
            $table->decimal('nilai', 10, 2)->default(0);
            $table->decimal('min_belanja', 15, 2)->default(0);
            $table->decimal('maks_diskon', 15, 2)->nullable();
            $table->boolean('aktif')->default(true);

            $table->timestamp('mulai_at')->nullable();
            $table->timestamp('berakhir_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('promoes');
    }
};