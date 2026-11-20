<?php

namespace App\Enums;

enum StatusPembayaranEnum: string
{
    case MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';
    case TERVERIFIKASI = 'terverifikasi';
    case DITOLAK = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            self::MENUNGGU_VERIFIKASI => 'Menunggu Verifikasi',
            self::TERVERIFIKASI => 'Terverifikasi',
            self::DITOLAK => 'Ditolak',
        };
    }
}
