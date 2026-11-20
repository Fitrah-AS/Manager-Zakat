<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TarifZakatFitrah extends Model
{
    use HasFactory;

    protected $table = 'tarif_zakat_fitrah';

    protected $fillable = [
        'periode_id',
        'beras_per_jiwa_kg',
        'nominal_uang',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'beras_per_jiwa_kg' => 'decimal:2',
            'nominal_uang' => 'decimal:2',
        ];
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }
}
