<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            // Tambah kolom maks_diskon setelah min_belanja
            // Digunakan sebagai batas maksimal diskon untuk voucher tipe persentase
            $table->decimal('maks_diskon', 15, 2)->nullable()->after('min_belanja');
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('maks_diskon');
        });
    }
};
