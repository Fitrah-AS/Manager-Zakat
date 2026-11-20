<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MutasiStokBeras extends Model
{
    use HasFactory;

    protected $table = 'mutasi_stok_beras';

    protected $fillable = [
        'periode_id',
        'tanggal_mutasi',
        'tipe',
        'jumlah_kg',
        'saldo_sebelum_kg',
        'saldo_sesudah_kg',
        'referensi_type',
        'referensi_id',
        'keterangan',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mutasi' => 'datetime',
            'jumlah_kg' => 'decimal:2',
            'saldo_sebelum_kg' => 'decimal:2',
            'saldo_sesudah_kg' => 'decimal:2',
        ];
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function referensi(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'referensi_type', 'referensi_id');
    }
}
