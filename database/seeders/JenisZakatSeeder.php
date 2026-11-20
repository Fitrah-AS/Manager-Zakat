<?php

namespace Database\Seeders;

use App\Models\JenisZakat;
use Illuminate\Database\Seeder;

class JenisZakatSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode' => 'FITRAH', 'nama_jenis' => 'Zakat Fitrah', 'kategori' => 'fitrah', 'deskripsi' => 'Zakat jiwa yang wajib ditunaikan setiap Muslim pada bulan Ramadhan sebelum shalat Idul Fitri.'],
            ['kode' => 'MAL_EMAS_PERAK', 'nama_jenis' => 'Zakat Mal - Emas & Perak', 'kategori' => 'mal', 'deskripsi' => 'Zakat atas kepemilikan emas dan perak yang telah mencapai nisab dan haul 1 tahun.'],
            ['kode' => 'MAL_PENGHASILAN', 'nama_jenis' => 'Zakat Mal - Penghasilan / Profesi', 'kategori' => 'mal', 'deskripsi' => 'Zakat atas pendapatan/penghasilan rutin maupun profesi sesuai ketentuan nisab setara 85 gr emas.'],
            ['kode' => 'MAL_PERDAGANGAN', 'nama_jenis' => 'Zakat Mal - Perdagangan', 'kategori' => 'mal', 'deskripsi' => 'Zakat atas modal kerja, persediaan barang dagangan, dan piutang lancar setelah dikurangi hutang jatuh tempo.'],
            ['kode' => 'MAL_TABUNGAN', 'nama_jenis' => 'Zakat Mal - Tabungan & Investasi', 'kategori' => 'mal', 'deskripsi' => 'Zakat atas simpanan tabungan, deposito, maupun aset investasi likuid yang mencapai nisab dan haul.'],
            ['kode' => 'MAL_PERTANIAN', 'nama_jenis' => 'Zakat Mal - Hasil Pertanian', 'kategori' => 'mal', 'deskripsi' => 'Zakat hasil panen pertanian saat panen tiba dengan nisab 5 wasaq (653 kg gabah / 520 kg beras).'],
        ];

        foreach ($items as $item) {
            JenisZakat::updateOrCreate(
                ['kode' => $item['kode']],
                array_merge($item, ['is_active' => true])
            );
        }
    }
}
