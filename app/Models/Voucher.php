<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model {
    protected $fillable = [
        'kode','nama','tipe','nilai','min_belanja','maks_diskon',
        'kuota','terpakai','maks_per_user','aktif','berlaku_mulai','berlaku_sampai',
    ];
    protected $casts = [
        'aktif'          => 'boolean',
        'berlaku_mulai'  => 'datetime',
        'berlaku_sampai' => 'datetime',
        'nilai'          => 'decimal:2',
        'min_belanja'    => 'decimal:2',
    ];

    public function hitungDiskon(float $subtotal): float {
        if ($subtotal < $this->min_belanja) return 0;
        $diskon = $this->tipe === 'persentase'
            ? $subtotal * ($this->nilai / 100)
            : (float) $this->nilai;
        return $this->maks_diskon ? min($diskon, (float)$this->maks_diskon) : $diskon;
    }

    public function scopeValid($q) {
        return $q->where('aktif', true)
            ->where(fn($x) => $x->whereNull('kuota')->orWhereRaw('terpakai < kuota'))
            ->where(fn($x) => $x->whereNull('berlaku_mulai')->orWhere('berlaku_mulai','<=',now()))
            ->where(fn($x) => $x->whereNull('berlaku_sampai')->orWhere('berlaku_sampai','>=',now()));
    }
}