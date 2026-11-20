<?php

namespace App\Enums;

enum StatusPenyaluranEnum: string
{
    case DIRENCANAKAN = 'direncanakan';
    case SELESAI = 'selesai';
    case DIBATALKAN = 'dibatalkan';

    public function label(): string
    {
        return match ($this) {
            self::DIRENCANAKAN => 'Direncanakan',
            self::SELESAI => 'Selesai',
            self::DIBATALKAN => 'Dibatalkan',
        };
    }
}
