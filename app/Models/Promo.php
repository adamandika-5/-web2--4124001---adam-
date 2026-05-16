<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model {
    protected $fillable = [
        'nama','slug','label','judul_html','deskripsi','tipe',
        'nilai','min_belanja','maks_diskon','aktif','mulai_at','berakhir_at',
    ];
    protected $casts = [
        'aktif'       => 'boolean',
        'mulai_at'    => 'datetime',
        'berakhir_at' => 'datetime',
        'nilai'       => 'decimal:2',
        'min_belanja' => 'decimal:2',
    ];
    public function scopeAktif($q) {
        return $q->where('aktif', true)
                 ->where('mulai_at', '<=', now())
                 ->where('berakhir_at', '>=', now());
    }
}