<?php

namespace App\Models;

use App\Enums\StatusKelayakanEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mustahik extends Model
{
    use HasFactory;

    protected $table = 'mustahik';

    protected $fillable = [
        'nama_lengkap',
        'nik',
        'alamat',
        'no_hp',
        'asnaf_id',
        'status_kelayakan',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'status_kelayakan' => StatusKelayakanEnum::class,
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function asnaf(): BelongsTo
    {
        return $this->belongsTo(Asnaf::class, 'asnaf_id');
    }

    public function survei(): HasMany
    {
        return $this->hasMany(SurveiMustahik::class, 'mustahik_id');
    }

    public function surveiTerakhir(): HasOne
    {
        return $this->hasOne(SurveiMustahik::class, 'mustahik_id')->latestOfMany('tanggal_survei');
    }

    public function penyaluranDetails(): HasMany
    {
        return $this->hasMany(PenyaluranDetail::class, 'mustahik_id');
    }
}
