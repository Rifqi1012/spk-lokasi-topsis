<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilPerhitungan extends Model
{
    protected $table = 'hasil_perhitungan';
    protected $primaryKey = 'hasil_id';

    protected $fillable = [
        'penilaian_id',
        'nilai_preferensi',
        'ranking',
        'tanggal_hitung',
    ];

    protected $casts = [
        'tanggal_hitung' => 'datetime',
    ];

    public function penilaian(): BelongsTo
    {
        return $this->belongsTo(Penilaian::class, 'penilaian_id', 'penilaian_id');
    }
}
