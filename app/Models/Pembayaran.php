<?php

namespace App\Models;

use App\Enums\StatusPembayaranEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use RuntimeException;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'kode_transaksi',
        'periode_id',
        'muzakki_id',
        'metode_pembayaran_id',
        'petugas_input_id',
        'tanggal_bayar',
        'total_uang',
        'total_beras_kg',
        'bukti_transfer',
        'status',
        'verified_by',
        'verified_at',
        'alasan_penolakan',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'datetime',
            'total_uang' => 'decimal:2',
            'total_beras_kg' => 'decimal:2',
            'status' => StatusPembayaranEnum::class,
            'verified_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (self $pembayaran): void {
            if ($pembayaran->status === StatusPembayaranEnum::TERVERIFIKASI) {
                throw new RuntimeException('Transaksi pembayaran yang sudah terverifikasi tidak boleh dihapus permanen.');
            }
        });
    }

    public function scopeTerverifikasi(Builder $query): Builder
    {
        return $query->where('status', StatusPembayaranEnum::TERVERIFIKASI->value);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function muzakki(): BelongsTo
    {
        return $this->belongsTo(Muzakki::class, 'muzakki_id');
    }

    public function metodePembayaran(): BelongsTo
    {
        return $this->belongsTo(MetodePembayaran::class, 'metode_pembayaran_id');
    }

    public function petugasInput(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_input_id');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PembayaranDetail::class, 'pembayaran_id');
    }

    public function masukStokBeras(): HasOne
    {
        return $this->hasOne(MasukStokBeras::class, 'pembayaran_id');
    }
}
