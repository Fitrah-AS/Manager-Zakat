<?php

namespace App\Models;

use App\Enums\BentukZakatEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenyaluranDetail extends Model
{
    use HasFactory;

    protected $table = 'penyaluran_detail';

    protected $fillable = [
        'penyaluran_id',
        'mustahik_id',
        'asnaf_id',
        'jenis_zakat_id',
        'bentuk_salur',
        'nominal_uang',
        'jumlah_beras_kg',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'bentuk_salur' => BentukZakatEnum::class,
            'nominal_uang' => 'decimal:2',
            'jumlah_beras_kg' => 'decimal:2',
        ];
    }

    public function penyaluran(): BelongsTo
    {
        return $this->belongsTo(Penyaluran::class, 'penyaluran_id');
    }

    public function mustahik(): BelongsTo
    {
        return $this->belongsTo(Mustahik::class, 'mustahik_id');
    }

    public function asnaf(): BelongsTo
    {
        return $this->belongsTo(Asnaf::class, 'asnaf_id');
    }

    public function jenisZakat(): BelongsTo
    {
        return $this->belongsTo(JenisZakat::class, 'jenis_zakat_id');
    }
}
