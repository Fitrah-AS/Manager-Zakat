<?php

namespace Database\Seeders;

use App\Models\MetodePembayaran;
use Illuminate\Database\Seeder;

class MetodePembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode' => 'TUNAI', 'nama_metode' => 'Tunai / Loket Masjid', 'tipe' => 'tunai', 'nama_bank' => null, 'no_rekening' => null, 'atas_nama' => 'Unit Pengumpul Zakat Masjid', 'keterangan' => 'Pembayaran tunai atau beras langsung melalui amil di loket.'],
            ['kode' => 'BSI_ZAKAT', 'nama_metode' => 'Transfer Bank Syariah Indonesia (BSI)', 'tipe' => 'transfer_bank', 'nama_bank' => 'Bank Syariah Indonesia (BSI)', 'no_rekening' => '7001234567', 'atas_nama' => 'LAZ Masjid Al-Ikhlas', 'keterangan' => 'Wajib mengunggah bukti transfer untuk diverifikasi Admin.'],
            ['kode' => 'QRIS_MASJID', 'nama_metode' => 'QRIS Lembaga Amil Zakat', 'tipe' => 'qris', 'nama_bank' => 'QRIS Nasional', 'no_rekening' => null, 'atas_nama' => 'LAZ Masjid Al-Ikhlas', 'keterangan' => 'Scan QRIS resmi dan unggah bukti transaksi.'],
        ];

        foreach ($items as $item) {
            MetodePembayaran::updateOrCreate(
                ['kode' => $item['kode']],
                array_merge($item, ['is_active' => true])
            );
        }
    }
}
