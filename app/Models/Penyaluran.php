<?php

namespace App\Models;

use App\Enums\StatusPenyaluranEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RuntimeException;

class Penyaluran extends Model
{
    use HasFactory;

    protected $table = 'penyaluran';

    protected $fillable = [
        'kode_penyaluran',
        'periode_id',
        'tanggal_penyaluran',
        'petugas_id',
        'status',
        'selesai_at',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_penyaluran' => 'datetime',
            'status' => StatusPenyaluranEnum::class,
            'selesai_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (self $penyaluran): void {
            if ($penyaluran->status === StatusPenyaluranEnum::SELESAI) {
                throw new RuntimeException('Transaksi penyaluran yang sudah selesai tidak boleh dihapus permanen.');
            }
        });
    }

    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('status', StatusPenyaluranEnum::SELESAI->value);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PenyaluranDetail::class, 'penyaluran_id');
    }
}
