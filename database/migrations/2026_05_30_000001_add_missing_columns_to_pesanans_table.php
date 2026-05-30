<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pesanans', function (Blueprint $table) {
            // Alamat
            if (!Schema::hasColumn('pesanans', 'kode_pos')) {
                $table->string('kode_pos')->nullable()->after('provinsi_tujuan');
            }

            // Pengiriman
            if (!Schema::hasColumn('pesanans', 'jenis_pengiriman')) {
                $table->string('jenis_pengiriman')->nullable()->after('kode_pos');
            }
            if (!Schema::hasColumn('pesanans', 'ekspedisi')) {
                $table->string('ekspedisi')->nullable()->after('jenis_pengiriman');
            }
            if (!Schema::hasColumn('pesanans', 'ongkir')) {
                $table->decimal('ongkir', 15, 2)->default(0)->after('ekspedisi');
            }

            // Keuangan
            if (!Schema::hasColumn('pesanans', 'diskon_produk')) {
                $table->decimal('diskon_produk', 15, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('pesanans', 'diskon_voucher')) {
                $table->decimal('diskon_voucher', 15, 2)->default(0)->after('diskon_produk');
            }
            if (!Schema::hasColumn('pesanans', 'dp_dibayar')) {
                $table->decimal('dp_dibayar', 15, 2)->nullable()->after('total');
            }

            // Pembayaran & status
            if (!Schema::hasColumn('pesanans', 'metode_bayar')) {
                $table->string('metode_bayar')->nullable()->after('dp_dibayar');
            }
            if (!Schema::hasColumn('pesanans', 'status_pembayaran')) {
                $table->string('status_pembayaran')->default('menunggu')->after('status');
            }

            // Catatan
            if (!Schema::hasColumn('pesanans', 'catatan')) {
                $table->text('catatan')->nullable()->after('status_pembayaran');
            }
            if (!Schema::hasColumn('pesanans', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable()->after('catatan');
            }

            // Timestamps pengiriman
            if (!Schema::hasColumn('pesanans', 'dikirim_at')) {
                $table->timestamp('dikirim_at')->nullable()->after('catatan_admin');
            }
            if (!Schema::hasColumn('pesanans', 'selesai_at')) {
                $table->timestamp('selesai_at')->nullable()->after('dikirim_at');
            }
        });
    }

    public function down(): void {
        Schema::table('pesanans', function (Blueprint $table) {
            $columns = [
                'kode_pos', 'jenis_pengiriman', 'ekspedisi', 'ongkir',
                'diskon_produk', 'diskon_voucher', 'dp_dibayar',
                'metode_bayar', 'status_pembayaran',
                'catatan', 'catatan_admin',
                'dikirim_at', 'selesai_at',
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('pesanans', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
