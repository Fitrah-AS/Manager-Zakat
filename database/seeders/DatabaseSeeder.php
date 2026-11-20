<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AsnafSeeder::class,
            JenisZakatSeeder::class,
            PeriodeDanTarifSeeder::class,
            MetodePembayaranSeeder::class,
            InformasiZakatSeeder::class,
        ]);
    }
}
