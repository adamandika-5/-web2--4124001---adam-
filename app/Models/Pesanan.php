<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesanan extends Model {
    use SoftDeletes;
    protected $fillable = [
        'nomor_pesanan','user_id','voucher_id',
        'penerima','telepon_penerima','alamat_pengiriman',
        'kota_tujuan','provinsi_tujuan','kode_pos',
        'jenis_pengiriman','ekspedisi','layanan_ekspedisi',
        'ongkir','estimasi_jarak_km',
        'subtotal','diskon_produk','diskon_voucher','total','dp_dibayar',
        'status','status_pembayaran','catatan','catatan_admin',
        'dikirim_at','selesai_at',
    ];
    protected $casts = [
        'dikirim_at'  => 'datetime',
        'selesai_at'  => 'datetime',
        'subtotal'    => 'decimal:2',
        'total'       => 'decimal:2',
        'ongkir'      => 'decimal:2',
    ];

    public function user()       { return $this->belongsTo(User::class); }
    public function voucher()    { return $this->belongsTo(Voucher::class); }
    public function items()      { return $this->hasMany(PesananItem::class); }
    public function pembayaran() { return $this->hasMany(Pembayaran::class); }

    public function getRingkasanProdukAttribute(): string {
        $first = $this->items->first();
        $extra = $this->items->count() - 1;
        return $first
            ? $first->nama_produk . ($extra > 0 ? " +{$extra} item" : '')
            : '-';
    }

    public static function generateNomor(): string {
        return 'SA-' . date('Ymd') . '-' . str_pad(random_int(0,9999), 4, '0', STR_PAD_LEFT);
    }
}