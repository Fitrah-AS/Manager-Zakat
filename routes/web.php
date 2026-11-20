<?php

use App\Http\Controllers\Admin\AktivitasLogController;
use App\Http\Controllers\Admin\AsnafController;
use App\Http\Controllers\Admin\InformasiZakatController;
use App\Http\Controllers\Admin\JenisZakatController;
use App\Http\Controllers\Admin\MetodePembayaranController;
use App\Http\Controllers\Admin\NisabController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MustahikController;
use App\Http\Controllers\MuzakkiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PenyaluranController;
use App\Http\Controllers\Public\BerandaController;
use App\Http\Controllers\Public\KalkulatorZakatController;
use App\Http\Controllers\StokBerasController;
use App\Http\Controllers\SurveiMustahikController;
use App\Http\Controllers\VerifikasiPembayaranController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik (Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/kalkulator-zakat', [KalkulatorZakatController::class, 'index'])->name('kalkulator.index');
Route::post('/kalkulator-zakat/hitung', [KalkulatorZakatController::class, 'hitung'])->name('kalkulator.hitung');

/*
|--------------------------------------------------------------------------
| Autentikasi Internal (Admin & Petugas)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Akses Bersama Admin & Petugas / Amil
    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('muzakki', MuzakkiController::class)
            ->only(['index', 'store', 'show', 'update', 'destroy']);

        Route::resource('mustahik', MustahikController::class)
            ->only(['index', 'store', 'show', 'update', 'destroy']);
        Route::post('mustahik/{mustahik}/survei', [SurveiMustahikController::class, 'store'])
            ->name('mustahik.survei.store');

        Route::resource('pembayaran', PembayaranController::class)
            ->only(['index', 'create', 'store', 'show']);
        Route::get('pembayaran/{pembayaran}/cetak', [PembayaranController::class, 'cetakStruk'])
            ->name('pembayaran.cetak');
        Route::get('pembayaran/{pembayaran}/pdf', [PembayaranController::class, 'downloadPdf'])
            ->name('pembayaran.pdf');
        Route::get('pembayaran/{pembayaran}/bukti', [PembayaranController::class, 'lihatBukti'])
            ->name('pembayaran.bukti');

        // Verifikasi Pembayaran oleh Petugas / Amil bertugas (serta dapat dipantau Admin)
        Route::get('verifikasi', [VerifikasiPembayaranController::class, 'index'])
            ->name('verifikasi.index');
        Route::get('verifikasi/{pembayaran}', [VerifikasiPembayaranController::class, 'show'])
            ->name('verifikasi.show');
        Route::patch('verifikasi/{pembayaran}/terima', [VerifikasiPembayaranController::class, 'terima'])
            ->name('verifikasi.terima');
        Route::patch('verifikasi/{pembayaran}/tolak', [VerifikasiPembayaranController::class, 'tolak'])
            ->name('verifikasi.tolak');

        // Manajemen Stok Beras (Kg)
        Route::get('stok-beras', [StokBerasController::class, 'index'])
            ->name('stok-beras.index');
        Route::post('stok-beras', [StokBerasController::class, 'store'])
            ->name('stok-beras.store');

        // Penyaluran Zakat ke Mustahik
        Route::resource('penyaluran', PenyaluranController::class)
            ->only(['index', 'create', 'store', 'show']);
        Route::patch('penyaluran/{penyaluran}/selesai', [PenyaluranController::class, 'selesai'])
            ->name('penyaluran.selesai');
        Route::patch('penyaluran/{penyaluran}/batal', [PenyaluranController::class, 'batal'])
            ->name('penyaluran.batal');
        Route::get('penyaluran/{penyaluran}/cetak', [PenyaluranController::class, 'cetakBukti'])
            ->name('penyaluran.cetak');
        Route::get('penyaluran/{penyaluran}/pdf', [PenyaluranController::class, 'downloadPdf'])
            ->name('penyaluran.pdf');

        // Laporan & BKU
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/{jenis}', [LaporanController::class, 'show'])->name('laporan.show');
        Route::get('laporan/{jenis}/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
        Route::get('laporan/{jenis}/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        Route::get('laporan/{jenis}/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
    });

    // Akses Khusus Admin / Pimpinan
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::resource('periode', PeriodeController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::patch('periode/{periode}/activate', [PeriodeController::class, 'activate'])->name('periode.activate');

        Route::resource('asnaf', AsnafController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('jenis-zakat', JenisZakatController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('nisab', NisabController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('metode-pembayaran', MetodePembayaranController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('informasi', InformasiZakatController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('aktivitas-log', [AktivitasLogController::class, 'index'])->name('aktivitas-log.index');
    });
});
