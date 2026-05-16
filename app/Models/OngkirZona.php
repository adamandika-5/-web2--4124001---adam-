<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OngkirZona extends Model {
    protected $fillable = [
        'kota','kabupaten','provinsi','jarak_km','zona',
        'tarif_pickup','tarif_engkel','tarif_truk','tersedia_armada',
    ];
    protected $casts = ['tersedia_armada' => 'boolean'];
}