<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerhitunganZakatMal extends Model
{
    use HasFactory;

    protected $table = 'perhitungan_zakat_mal';

    protected $fillable = [
        'pembayaran_detail_id',
        'nisab_id',
        'jenis_harta',
        'metode_hitung',
        'nilai_harta_kotor',
        'hutang_jatuh_tempo',
        'harta_bersih',
        'nilai_nisab_saat_hitung',
        'sudah_haul',
        'memenuhi_nisab',
        'persentase_zakat',
        'hasil_zakat',
        'catatan_perhitungan',
    ];

    protected function casts(): array
    {
        return [
            'nilai_harta_kotor' => 'decimal:2',
            'hutang_jatuh_tempo' => 'decimal:2',
            'harta_bersih' => 'decimal:2',
            'nilai_nisab_saat_hitung' => 'decimal:2',
            'sudah_haul' => 'boolean',
            'memenuhi_nisab' => 'boolean',
            'persentase_zakat' => 'decimal:2',
            'hasil_zakat' => 'decimal:2',
        ];
    }

    public function pembayaranDetail(): BelongsTo
    {
        return $this->belongsTo(PembayaranDetail::class, 'pembayaran_detail_id');
    }

    public function nisab(): BelongsTo
    {
        return $this->belongsTo(Nisab::class, 'nisab_id');
    }
}
