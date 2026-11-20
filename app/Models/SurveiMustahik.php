<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveiMustahik extends Model
{
    use HasFactory;

    protected $table = 'survei_mustahik';

    protected $fillable = [
        'mustahik_id',
        'petugas_id',
        'tanggal_survei',
        'kondisi_ekonomi',
        'jumlah_tanggungan',
        'hasil_survei',
        'catatan_petugas',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_survei' => 'date',
            'jumlah_tanggungan' => 'integer',
        ];
    }

    public function mustahik(): BelongsTo
    {
        return $this->belongsTo(Mustahik::class, 'mustahik_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
