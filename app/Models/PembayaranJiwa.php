<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranJiwa extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_jiwa';

    protected $fillable = [
        'pembayaran_detail_id',
        'nama_anggota',
        'hubungan_keluarga',
    ];

    public function pembayaranDetail(): BelongsTo
    {
        return $this->belongsTo(PembayaranDetail::class, 'pembayaran_detail_id');
    }
}
