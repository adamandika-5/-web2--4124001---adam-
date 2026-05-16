<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model {
    protected $fillable = [
        'user_id','ikon','aksi','deskripsi',
        'model_type','model_id',
        'data_lama','data_baru',
        'ip_address','user_agent',
    ];
    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
    ];

    public function user() { return $this->belongsTo(User::class); }

    public static function catat(
        string $aksi,
        string $deskripsi,
        string $ikon = '📝',
        $model = null,
        array $dataLama = [],
        array $dataBaru = []
    ): void {
        static::create([
            'user_id'    => auth()->id(),
            'ikon'       => $ikon,
            'aksi'       => $aksi,
            'deskripsi'  => $deskripsi,
            'model_type' => $model ? get_class($model) : null,
            'model_id'   => $model?->id,
            'data_lama'  => $dataLama ?: null,
            'data_baru'  => $dataBaru ?: null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}