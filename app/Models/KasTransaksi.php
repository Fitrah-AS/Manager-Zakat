<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class KasTransaksi extends Model
{
    use HasFactory;

    protected $table = 'kas_transaksi';

    protected $fillable = [
        'periode_id',
        'tanggal_transaksi',
        'jenis_arus',
        'kategori',
        'nominal',
        'saldo_setelah',
        'referensi_type',
        'referensi_id',
        'keterangan',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_transaksi' => 'datetime',
            'nominal' => 'decimal:2',
            'saldo_setelah' => 'decimal:2',
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
