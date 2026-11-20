<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Muzakki extends Model
{
    use HasFactory;

    protected $table = 'muzakki';

    protected $fillable = [
        'nama_lengkap',
        'nik',
        'alamat',
        'no_hp',
        'email',
    ];

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'muzakki_id');
    }
}
