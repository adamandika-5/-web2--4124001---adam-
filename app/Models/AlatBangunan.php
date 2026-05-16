<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class AlatBangunan extends Model {
    use SoftDeletes;
    protected $fillable = [
        'nama','slug','kategori_alat','deskripsi','gambar',
        'tarif_harian','tarif_mingguan','tarif_bulanan',
        'deposit','denda_per_hari','jumlah_unit','tersedia',
        'kondisi','aktif','catatan',
    ];
    protected $casts = [
        'aktif'       => 'boolean',
        'tarif_harian'=> 'decimal:2',
        'deposit'     => 'decimal:2',
        'denda_per_hari' => 'decimal:2',
    ];

    protected static function boot() {
        parent::boot();
        static::creating(fn($m) => $m->slug = Str::slug($m->nama));
    }

    public function bookings()       { return $this->hasMany(BookingAlat::class, 'alat_id'); }
    public function getStatusAttribute(): string { return $this->tersedia > 0 ? 'tersedia' : 'disewa'; }
    public function scopeAktif($q)   { return $q->where('aktif', true); }
    public function scopeTersedia($q){ return $q->where('tersedia', '>', 0); }
}