<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penilaian extends Model
{
    use SoftDeletes;

    protected $table = 'penilaian';
    protected $primaryKey = 'penilaian_id';

    protected $fillable = [
        'lokasi_id',
        'observasi_id',
        'user_id',
        'tanggal_penilaian',
    ];

    protected $casts = [
        'tanggal_penilaian' => 'date',
    ];

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id', 'lokasi_id');
    }

    public function observasiLokasi(): BelongsTo
    {
        return $this->belongsTo(ObservasiLokasi::class, 'observasi_id', 'observasi_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detailPenilaians(): HasMany
    {
        return $this->hasMany(DetailPenilaian::class, 'penilaian_id', 'penilaian_id');
    }

    public function hasilPerhitungan(): HasOne
    {
        return $this->hasOne(HasilPerhitungan::class, 'penilaian_id', 'penilaian_id');
    }
}
