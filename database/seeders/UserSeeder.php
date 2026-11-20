<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator LAZ',
                'email' => 'admin@zakat.test',
                'password' => Hash::make('AdminZakat123!'),
                'role' => UserRoleEnum::ADMIN->value,
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['username' => 'petugas1'],
            [
                'name' => 'Amil Zakat Lapangan',
                'email' => 'petugas1@zakat.test',
                'password' => Hash::make('PetugasZakat123!'),
                'role' => UserRoleEnum::PETUGAS->value,
                'is_active' => true,
            ]
        );
    }
}
