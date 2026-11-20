<?php

namespace App\Enums;

enum BentukZakatEnum: string
{
    case UANG = 'uang';
    case BERAS = 'beras';

    public function label(): string
    {
        return match ($this) {
            self::UANG => 'Uang (Rupiah)',
            self::BERAS => 'Beras (Kilogram)',
        };
    }
}
