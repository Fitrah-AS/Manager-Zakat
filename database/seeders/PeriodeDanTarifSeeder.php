<?php

namespace Database\Seeders;

use App\Models\Nisab;
use App\Models\Periode;
use App\Models\TarifZakatFitrah;
use Illuminate\Database\Seeder;

class PeriodeDanTarifSeeder extends Seeder
{
    public function run(): void
    {
        $periode = Periode::updateOrCreate(
            ['tahun_hijriah' => '1447 H'],
            [
                'nama_periode' => 'Ramadhan & Tahun Zakat 1447 H / 2026 M',
                'tahun_masehi' => '2026',
                'tanggal_mulai' => '2026-01-01',
                'tanggal_selesai' => '2026-12-31',
                'batas_akhir_fitrah' => '2026-03-20 06:30:00',
                'is_active' => true,
                'keterangan' => 'Periode aktif pengelolaan zakat fitrah dan zakat mal.',
            ]
        );

        TarifZakatFitrah::updateOrCreate(
            ['periode_id' => $periode->id],
            [
                'beras_per_jiwa_kg' => 2.50,
                'nominal_uang' => 45000.00,
                'keterangan' => 'Standar 2,5 kg beras atau uang senilai Rp 45.000 per jiwa (dapat diubah Admin sesuai ketetapan BAZNAS/setempat).',
            ]
        );

        $hargaEmas = 1500000.00;
        $nisab85Gram = $hargaEmas * 85;

        $nisabs = [
            [
                'jenis_harta' => 'emas_perak',
                'nama_standar' => 'Nisab Emas & Perak (85 gr Emas)',
                'basis_nisab' => '85 gram emas murni (atau 595 gram perak)',
                'harga_emas_per_gram' => $hargaEmas,
                'gram_emas_nisab' => 85.00,
                'nisab_pertanian_kg' => null,
                'nilai_nisab_uang' => $nisab85Gram,
                'kadar_zakat_persen' => 2.50,
                'ketentuan_haul' => 'Wajib telah dimiliki selama 1 tahun Hijriah penuh (haul).',
                'catatan' => 'Zakat 2,5% dari total nilai emas/perak bersih yang melampaui nisab.',
            ],
            [
                'jenis_harta' => 'penghasilan',
                'nama_standar' => 'Nisab Zakat Penghasilan / Profesi',
                'basis_nisab' => 'Setara 85 gram emas per tahun (dibagi 12 untuk perhitungan bulanan)',
                'harga_emas_per_gram' => $hargaEmas,
                'gram_emas_nisab' => 85.00,
                'nisab_pertanian_kg' => null,
                'nilai_nisab_uang' => $nisab85Gram,
                'kadar_zakat_persen' => 2.50,
                'ketentuan_haul' => 'Dapat ditunaikan setiap bulan saat menerima penghasilan jika mencapai nisab bulanan, atau diakumulasi 1 tahun.',
                'catatan' => 'Mendukung metode perhitungan bruto maupun neto (setelah kebutuhan pokok/hutang jatuh tempo).',
            ],
            [
                'jenis_harta' => 'perdagangan',
                'nama_standar' => 'Nisab Zakat Perdagangan',
                'basis_nisab' => 'Setara 85 gram emas murni',
                'harga_emas_per_gram' => $hargaEmas,
                'gram_emas_nisab' => 85.00,
                'nisab_pertanian_kg' => null,
                'nilai_nisab_uang' => $nisab85Gram,
                'kadar_zakat_persen' => 2.50,
                'ketentuan_haul' => 'Usaha perdagangan telah berjalan 1 tahun Hijriah (haul).',
                'catatan' => 'Rumus: (Modal Diputar + Laba + Piutang Lancar - Hutang Jangka Pendek) x 2,5%.',
            ],
            [
                'jenis_harta' => 'tabungan_investasi',
                'nama_standar' => 'Nisab Tabungan & Investasi',
                'basis_nisab' => 'Setara 85 gram emas murni',
                'harga_emas_per_gram' => $hargaEmas,
                'gram_emas_nisab' => 85.00,
                'nisab_pertanian_kg' => null,
                'nilai_nisab_uang' => $nisab85Gram,
                'kadar_zakat_persen' => 2.50,
                'ketentuan_haul' => 'Saldo terendah/simpanan mengendap selama 1 tahun Hijriah (haul).',
                'catatan' => 'Rumus: (Total Saldo Tabungan/Investasi - Hutang Jatuh Tempo) x 2,5%.',
            ],
            [
                'jenis_harta' => 'pertanian',
                'nama_standar' => 'Nisab Zakat Pertanian (5 Wasaq)',
                'basis_nisab' => '653 kg gabah kering atau 520 kg beras',
                'harga_emas_per_gram' => 0,
                'gram_emas_nisab' => 0,
                'nisab_pertanian_kg' => 653.00,
                'nilai_nisab_uang' => 0,
                'kadar_zakat_persen' => 5.00,
                'ketentuan_haul' => 'Tidak disyaratkan haul; ditunaikan setiap kali panen.',
                'catatan' => 'Kadar 5% jika menggunakan irigasi berbiaya, atau 10% jika diairi air hujan/sungai alami.',
            ],
        ];

        foreach ($nisabs as $nisab) {
            Nisab::updateOrCreate(
                ['periode_id' => $periode->id, 'jenis_harta' => $nisab['jenis_harta']],
                array_merge($nisab, ['is_active' => true])
            );
        }
    }
}
