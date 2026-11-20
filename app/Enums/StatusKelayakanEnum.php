<?php

namespace App\Enums;

enum StatusKelayakanEnum: string
{
    case MENUNGGU_SURVEI = 'menunggu_survei';
    case LAYAK = 'layak';
    case TIDAK_LAYAK = 'tidak_layak';

    public function label(): string
    {
        return match ($this) {
            self::MENUNGGU_SURVEI => 'Menunggu Survei',
            self::LAYAK => 'Layak',
            self::TIDAK_LAYAK => 'Tidak Layak',
        };
    }
}
