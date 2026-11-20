<?php

namespace App\Models;

use App\Enums\BentukZakatEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PembayaranDetail extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_detail';

    protected $fillable = [
        'pembayaran_id',
        'jenis_zakat_id',
        'bentuk_bayar',
        'jumlah_jiwa',
        'nominal_uang',
        'jumlah_beras_kg',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'bentuk_bayar' => BentukZakatEnum::class,
            'jumlah_jiwa' => 'integer',
            'nominal_uang' => 'decimal:2',
            'jumlah_beras_kg' => 'decimal:2',
        ];
    }

    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id');
    }

    public function jenisZakat(): BelongsTo
    {
        return $this->belongsTo(JenisZakat::class, 'jenis_zakat_id');
    }

    public function jiwa(): HasMany
    {
        return $this->hasMany(PembayaranJiwa::class, 'pembayaran_detail_id');
    }

    public function perhitunganZakatMal(): HasOne
    {
        return $this->hasOne(PerhitunganZakatMal::class, 'pembayaran_detail_id');
    }
}
