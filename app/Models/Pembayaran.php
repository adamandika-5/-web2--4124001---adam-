<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model {
    protected $fillable = [
        'pesanan_id','user_id','metode','bank','no_rekening',
        'nama_pengirim','jumlah','bukti_path',
        'status','catatan_admin','dikonfirmasi_at','dikonfirmasi_oleh',
    ];
    protected $casts = [
        'dikonfirmasi_at' => 'datetime',
        'jumlah'          => 'decimal:2',
    ];
    public function pesanan()           { return $this->belongsTo(Pesanan::class); }
    public function user()              { return $this->belongsTo(User::class); }
    public function dikonfirmasiOleh()  { return $this->belongsTo(User::class, 'dikonfirmasi_oleh'); }
}