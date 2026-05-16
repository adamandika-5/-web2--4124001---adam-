<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AlamatUser extends Model {
    protected $fillable = [
        'user_id','label','penerima','telepon','alamat_lengkap',
        'kelurahan','kecamatan','kota','provinsi','kode_pos','is_utama',
    ];
    protected $casts = ['is_utama' => 'boolean'];
    public function user() { return $this->belongsTo(User::class); }
}