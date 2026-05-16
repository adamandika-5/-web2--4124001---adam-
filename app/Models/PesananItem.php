<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PesananItem extends Model {
    protected $fillable = [
        'pesanan_id','produk_id','nama_produk',
        'harga_satuan','harga_promo','qty','satuan','subtotal',
    ];
    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'harga_promo'  => 'decimal:2',
        'subtotal'     => 'decimal:2',
    ];
    public function pesanan() { return $this->belongsTo(Pesanan::class); }
    public function produk()  { return $this->belongsTo(Produk::class)->withTrashed(); }
}