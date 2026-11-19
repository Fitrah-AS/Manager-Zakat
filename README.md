# Sistem Informasi Pengelolaan Zakat Fitrah dan Zakat Mal Berbasis Website

Aplikasi manajemen zakat fitrah dan zakat mal berbasis website untuk lingkungan masjid atau Lembaga Amil Zakat (LAZ) yang dibangun menggunakan **Laravel 13**, **PHP 8.3+**, **MySQL**, **Blade Template**, **Tailwind CSS**, **Vanilla JavaScript**, dan **Vite**.

---

## Fitur Utama

1. **Portal Publik & Transparansi (Tanpa Login)**
   - Informasi lengkap Zakat Fitrah dan Zakat Mal.
   - Tarif zakat fitrah aktif (beras per jiwa & nominal uang) serta batas waktu pembayaran.
   - Tabel ketentuan nisab dan haul per jenis harta.
   - **Kalkulator Zakat Mal** untuk 5 jenis harta (`Emas & Perak`, `Penghasilan Bruto/Neto`, `Perdagangan`, `Tabungan & Investasi`, `Pertanian 5%/10%`) dilengkapi catatan bahwa kalkulator hanya sebagai alat bantu hitung administratif, bukan fatwa agama.
   - Statistik real-time penerimaan dan penyaluran per 8 asnaf langsung dari database.

2. **Operasional Petugas / Amil & Admin**
   - **Dashboard Real-Time**: Jumlah muzakki, mustahik, total penerimaan uang (Rp) & beras (kg), penyaluran selesai, stok beras, transaksi menunggu verifikasi, grafik penerimaan & penyaluran asnaf, serta log aktivitas terbaru.
   - **Manajemen Muzakki & Mustahik**: CRUD lengkap + survei kelayakan mustahik beserta titik koordinat (latitude & longitude).
   - **Pembayaran Zakat Fitrah & Mal**: Dukungan anggota keluarga (multi-jiwa), hitung otomatis sesuai tarif periode aktif, pembagian pembayaran tunai (otomatis terverifikasi) vs transfer (menunggu verifikasi + unggah bukti transfer aman di disk privat), cetak struk & unduh PDF.
   - **Verifikasi Pembayaran oleh Petugas/Amil**: Petugas/Amil yang bertugas (serta Admin) dapat memeriksa bukti transfer, menerima pembayaran agar tercatat ke Buku Kas Umum / Stok Beras, atau menolak dengan alasan.
   - **Manajemen Stok Beras (Kg)**: Mutasi beras masuk/keluar terpisah dari kas Rupiah, termasuk pemasukan beras manual.
   - **Penyaluran Zakat**: Penyaluran ke mustahik berstatus `Layak`, validasi saldo kas/beras anti-minus, cetak & PDF tanda terima penyaluran.
   - **8 Laporan & Buku Kas Umum (BKU)**: Penerimaan, Zakat Fitrah, Zakat Mal, Penyaluran per Asnaf, Data Muzakki, Data Mustahik, Buku Kas Umum (Rupiah), dan Mutasi Stok Beras (Kg) — mendukung cetak browser, ekspor **PDF**, dan ekspor **Excel (`.xlsx`)**.

3. **Administrasi & Pengaturan Master (Khusus Admin / Pimpinan)**
   - Manajemen Periode (Tahun Hijriah, Masehi, batas akhir fitrah, periode aktif).
   - Tarif Zakat Fitrah per periode & Standar Nisab Zakat Mal.
   - Master 8 Asnaf, Jenis Zakat, Metode Pembayaran, Informasi Lembaga, Akun Pengguna, dan Audit Log Aktivitas.

---

## Panduan Instalasi & Menjalankan di Laragon (Lokal Windows)

### 1. Prasyarat Laragon
Pastikan Laragon Anda sudah menggunakan:
- **PHP >= 8.3** (aktifkan ekstensi PHP: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`, `zip`, `xml`)
- **MySQL 8.0+ / MariaDB 10.6+**
- **Composer**
- **Node.js & NPM**

### 2. Penempatan Folder Project
Salin folder `zakat-app` ke direktori Laragon Anda (misalnya `C:\laragon\www\zakat-app`).
Jika menggunakan Virtual Host Laragon otomatis (`zakat-app.test`), pastikan **Document Root** mengarah ke folder `public`:
`C:\laragon\www\zakat-app\public`

### 3. Konfigurasi Database & File `.env`
1. Buka Laragon -> klik **Start All** -> buka **HeidiSQL / phpMyAdmin** -> buat database baru:
   ```sql
   CREATE DATABASE zakat_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
2. Salin `.env.example` menjadi `.env` di dalam folder `zakat-app`:
   ```bash
   copy .env.example .env
   ```
3. Sesuaikan pengaturan database di `.env`:
   ```env
   APP_NAME="Sistem Informasi Zakat"
   APP_URL=http://zakat-app.test
   APP_TIMEZONE=Asia/Jakarta

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=zakat_app
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### 4. Instalasi Dependensi & Build Aset
Buka **Laragon Terminal** di dalam folder `zakat-app`:
```bash
composer install
php artisan key:generate
npm install
npm run build
```

### 5. Migrasi Database & Data Awal (Seeder)
```bash
php artisan migrate --seed
```
*(Seeder otomatis mengisi 8 Asnaf, Jenis Zakat, Periode Aktif `1447 H`, Tarif Zakat Fitrah, Standar Nisab 5 Jenis Harta, Metode Pembayaran, Informasi Lembaga, serta akun bawaan Admin & Petugas.)*

### 6. Menjalankan Aplikasi
- Melalui Laragon Virtual Host: buka `http://zakat-app.test`
- Atau via Artisan server lokal:
  ```bash
  php artisan serve
  ```
  lalu buka `http://127.0.0.1:8000`

### 7. Menjalankan Pengujian Otomatis (PHPUnit)
```bash
php artisan test
```

---

## Akun Bawaan (Hasil Seeder)

| Peran | Username | Email | Password |
| :--- | :--- | :--- | :--- |
| **Admin / Pimpinan** | `admin` | `admin@laz.test` | `AdminZakat123!` |
| **Petugas / Amil** | `petugas1` | `petugas@laz.test` | `PetugasZakat123!` |

> Segera ubah password akun bawaan setelah login pertama kali melalui menu **Akun & Hak Akses**.
"# Manager-Zakat" 
