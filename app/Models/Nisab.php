<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nisab extends Model
{
    use HasFactory;

    protected $table = 'nisab';

    protected $fillable = [
        'periode_id',
        'jenis_harta',
        'nama_standar',
        'basis_nisab',
        'harga_emas_per_gram',
        'gram_emas_nisab',
        'nisab_pertanian_kg',
        'nilai_nisab_uang',
        'kadar_zakat_persen',
        'ketentuan_haul',
        'catatan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga_emas_per_gram' => 'decimal:2',
            'gram_emas_nisab' => 'decimal:2',
            'nisab_pertanian_kg' => 'decimal:2',
            'nilai_nisab_uang' => 'decimal:2',
            'kadar_zakat_persen' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function perhitunganZakatMal(): HasMany
    {
        return $this->hasMany(PerhitunganZakatMal::class, 'nisab_id');
    }
}
