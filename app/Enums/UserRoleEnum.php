<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case ADMIN = 'admin';
    case PETUGAS = 'petugas';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin / Pimpinan',
            self::PETUGAS => 'Petugas / Amil',
        };
    }
}
