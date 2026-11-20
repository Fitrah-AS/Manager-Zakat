<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisZakat extends Model
{
    use HasFactory;

    protected $table = 'jenis_zakat';

    protected $fillable = [
        'kode',
        'nama_jenis',
        'kategori',
        'deskripsi',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function pembayaranDetails(): HasMany
    {
        return $this->hasMany(PembayaranDetail::class, 'jenis_zakat_id');
    }

    public function penyaluranDetails(): HasMany
    {
        return $this->hasMany(PenyaluranDetail::class, 'jenis_zakat_id');
    }
}
