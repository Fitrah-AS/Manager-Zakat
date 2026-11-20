<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asnaf extends Model
{
    use HasFactory;

    protected $table = 'asnaf';

    protected $fillable = [
        'kode',
        'nama_asnaf',
        'deskripsi',
        'urutan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function mustahik(): HasMany
    {
        return $this->hasMany(Mustahik::class, 'asnaf_id');
    }

    public function penyaluranDetails(): HasMany
    {
        return $this->hasMany(PenyaluranDetail::class, 'asnaf_id');
    }
}
