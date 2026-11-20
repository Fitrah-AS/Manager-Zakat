<?php

namespace Database\Seeders;

use App\Models\Asnaf;
use Illuminate\Database\Seeder;

class AsnafSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode' => 'FAKIR', 'nama_asnaf' => 'Fakir', 'deskripsi' => 'Orang yang hampir tidak memiliki apa-apa sehingga tidak mampu memenuhi kebutuhan pokok hidup.', 'urutan' => 1],
            ['kode' => 'MISKIN', 'nama_asnaf' => 'Miskin', 'deskripsi' => 'Orang yang memiliki harta/penghasilan namun tidak mencukupi kebutuhan dasar sehari-hari.', 'urutan' => 2],
            ['kode' => 'AMIL', 'nama_asnaf' => 'Amil', 'deskripsi' => 'Pengelola yang diberi tugas mengumpulkan dan mendistribusikan zakat.', 'urutan' => 3],
            ['kode' => 'MUALAF', 'nama_asnaf' => 'Mualaf', 'deskripsi' => 'Orang yang baru masuk Islam atau yang hatinya perlu dikuatkan dalam Islam.', 'urutan' => 4],
            ['kode' => 'RIQAB', 'nama_asnaf' => 'Riqab', 'deskripsi' => 'Memerdekakan budak atau membebaskan muslim yang tertawan/terbelenggu.', 'urutan' => 5],
            ['kode' => 'GHARIMIN', 'nama_asnaf' => 'Gharimin', 'deskripsi' => 'Orang yang berhutang untuk memenuhi kebutuhan hidup halal dan tidak sanggup melunasinya.', 'urutan' => 6],
            ['kode' => 'FISABILILLAH', 'nama_asnaf' => 'Fisabilillah', 'deskripsi' => 'Orang atau kegiatan yang berjuang di jalan Allah SWT (dakwah, pendidikan, kemaslahatan umat).', 'urutan' => 7],
            ['kode' => 'IBNU_SABIL', 'nama_asnaf' => 'Ibnu Sabil', 'deskripsi' => 'Musafir yang kehabisan bekal dalam perjalanan ketaatan bukan maksiat.', 'urutan' => 8],
        ];

        foreach ($items as $item) {
            Asnaf::updateOrCreate(
                ['kode' => $item['kode']],
                array_merge($item, ['is_active' => true])
            );
        }
    }
}
