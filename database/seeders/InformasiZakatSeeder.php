<?php

namespace Database\Seeders;

use App\Models\InformasiZakat;
use Illuminate\Database\Seeder;

class InformasiZakatSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'slug' => 'ketentuan-zakat-fitrah',
                'judul' => 'Ketentuan & Tata Cara Zakat Fitrah',
                'kategori' => 'fitrah',
                'konten' => 'Zakat fitrah wajib ditunaikan oleh setiap Muslim yang mampu pada bulan Ramadhan hingga sebelum pelaksanaan shalat Idul Fitri. Besaran zakat fitrah adalah 1 sha\' (setara 2,5 kg atau 3,5 liter makanan pokok/beras) per jiwa, atau dapat dikonversi dalam bentuk uang sesuai ketetapan tarif periode aktif lembaga.',
            ],
            [
                'slug' => 'ketentuan-zakat-mal',
                'judul' => 'Ketentuan Nisab, Haul, dan Jenis Harta Zakat Mal',
                'kategori' => 'mal',
                'konten' => 'Zakat mal dikenakan atas harta yang dimiliki secara penuh, halal, mencapai nisab, dan umumnya telah melewati masa kepemilikan 1 tahun Hijriah (haul) kecuali zakat pertanian yang ditunaikan saat panen dan zakat penghasilan yang dapat ditunaikan per bulan atau per tahun. Setiap jenis harta memiliki ketentuan nisab dan kadar zakat tersendiri.',
            ],
            [
                'slug' => 'profil-lembaga-amil-zakat',
                'judul' => 'Lembaga Amil Zakat Masjid Al-Ikhlas',
                'kategori' => 'lembaga',
                'konten' => 'Lembaga Amil Zakat (LAZ) Masjid Al-Ikhlas bertugas menghimpun, memverifikasi, mengelola, dan menyalurkan zakat fitrah maupun zakat mal secara amanah, transparan, dan tepat sasaran kepada 8 golongan asnaf berdasarkan hasil survei kelayakan mustahik.',
            ],
        ];

        foreach ($items as $item) {
            InformasiZakat::updateOrCreate(
                ['slug' => $item['slug']],
                array_merge($item, ['is_published' => true])
            );
        }
    }
}
