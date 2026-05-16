<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingAlat extends Model {
    use SoftDeletes;
    protected $fillable = [
        'nomor_booking','user_id','alat_id',
        'tanggal_mulai','tanggal_selesai','tanggal_kembali_aktual',
        'durasi_hari','tarif_per_hari','total_sewa',
        'deposit','denda','hari_terlambat','total_bayar',
        'status','alamat_penggunaan','catatan',
    ];
    protected $casts = [
        'tanggal_mulai'           => 'date',
        'tanggal_selesai'         => 'date',
        'tanggal_kembali_aktual'  => 'date',
        'total_sewa'              => 'decimal:2',
        'total_bayar'             => 'decimal:2',
        'denda'                   => 'decimal:2',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function alat() { return $this->belongsTo(AlatBangunan::class, 'alat_id'); }

    public function hitungDenda(): float {
        if (!$this->tanggal_kembali_aktual) return 0;
        $terlambat = $this->tanggal_selesai->diffInDays($this->tanggal_kembali_aktual, false);
        if ($terlambat <= 0) return 0;
        return $terlambat * $this->alat->denda_per_hari;
    }

    public static function generateNomor(): string {
        return 'SWA-' . date('Ymd') . '-' . str_pad(random_int(0,9999), 4, '0', STR_PAD_LEFT);
    }
}