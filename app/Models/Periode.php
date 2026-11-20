<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Periode extends Model
{
    use HasFactory;

    protected $table = 'periode';

    protected $fillable = [
        'nama_periode',
        'tahun_masehi',
        'tahun_hijriah',
        'tanggal_mulai',
        'tanggal_selesai',
        'batas_akhir_fitrah',
        'is_active',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'batas_akhir_fitrah' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function tarifFitrah(): HasOne
    {
        return $this->hasOne(TarifZakatFitrah::class, 'periode_id');
    }

    public function nisabs(): HasMany
    {
        return $this->hasMany(Nisab::class, 'periode_id');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'periode_id');
    }

    public function masukStokBeras(): HasMany
    {
        return $this->hasMany(MasukStokBeras::class, 'periode_id');
    }

    public function mutasiStokBeras(): HasMany
    {
        return $this->hasMany(MutasiStokBeras::class, 'periode_id');
    }

    public function penyaluran(): HasMany
    {
        return $this->hasMany(Penyaluran::class, 'periode_id');
    }

    public function kasTransaksi(): HasMany
    {
        return $this->hasMany(KasTransaksi::class, 'periode_id');
    }

    public function sudahDigunakan(): bool
    {
        return $this->pembayaran()->exists()
            || $this->penyaluran()->exists()
            || $this->masukStokBeras()->exists()
            || $this->kasTransaksi()->exists();
    }
}
