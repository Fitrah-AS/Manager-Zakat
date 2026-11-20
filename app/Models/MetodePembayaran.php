<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayaran';

    protected $fillable = [
        'kode',
        'nama_metode',
        'tipe',
        'nama_bank',
        'no_rekening',
        'atas_nama',
        'keterangan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function isTunai(): bool
    {
        return $this->tipe === 'tunai';
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'metode_pembayaran_id');
    }
}
