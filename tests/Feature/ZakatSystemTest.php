<?php

namespace Tests\Feature;

use App\Enums\StatusKelayakanEnum;
use App\Enums\StatusPembayaranEnum;
use App\Enums\StatusPenyaluranEnum;
use App\Enums\UserRoleEnum;
use App\Models\Asnaf;
use App\Models\JenisZakat;
use App\Models\KasTransaksi;
use App\Models\MetodePembayaran;
use App\Models\Mustahik;
use App\Models\MutasiStokBeras;
use App\Models\Muzakki;
use App\Models\Pembayaran;
use App\Models\Periode;
use App\Models\User;
use App\Services\PembayaranService;
use App\Services\PenyaluranService;
use App\Services\VerifikasiPembayaranService;
use App\Services\ZakatCalculatorService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class ZakatSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_halaman_publik_dan_kalkulator_dapat_diakses_tanpa_login(): void
    {
        $this->get(route('beranda'))
            ->assertOk()
            ->assertSee('Transparansi Pengelolaan Zakat');

        $this->get(route('kalkulator.index'))
            ->assertOk()
            ->assertSee('Kalkulator Zakat Mal')
            ->assertSee('bukan fatwa agama');
    }

    public function test_hak_akses_petugas_dan_admin_berjalan_sesuai_peran(): void
    {
        $petugas = User::where('role', UserRoleEnum::PETUGAS->value)->firstOrFail();
        $admin = User::where('role', UserRoleEnum::ADMIN->value)->firstOrFail();

        // Petugas diblokir dari modul khusus Admin
        $this->actingAs($petugas)
            ->get(route('admin.users.index'))
            ->assertForbidden();

        // Petugas dapat mengakses Verifikasi Pembayaran, Stok Beras, Penyaluran, dan Laporan
        $this->actingAs($petugas)->get(route('verifikasi.index'))->assertOk();
        $this->actingAs($petugas)->get(route('stok-beras.index'))->assertOk();
        $this->actingAs($petugas)->get(route('penyaluran.index'))->assertOk();
        $this->actingAs($petugas)->get(route('laporan.index'))->assertOk();

        // Admin dapat mengakses modul master
        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.periode.index'))->assertOk();
    }

    public function test_pembayaran_tunai_otomatis_terverifikasi_dan_transfer_diverifikasi_petugas(): void
    {
        Storage::fake('local');

        $petugas = User::where('role', UserRoleEnum::PETUGAS->value)->firstOrFail();
        $this->actingAs($petugas);

        $muzakki = Muzakki::create([
            'nama_lengkap' => 'Bapak Ahmad',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Masjid No. 1',
        ]);

        $fitrah = JenisZakat::where('kode', 'ZKT-FITRAH')->firstOrFail();
        $tunai = MetodePembayaran::where('is_tunai', true)->firstOrFail();
        $transfer = MetodePembayaran::where('is_tunai', false)->firstOrFail();

        /** @var PembayaranService $pembayaranService */
        $pembayaranService = app(PembayaranService::class);
        /** @var VerifikasiPembayaranService $verifikasiService */
        $verifikasiService = app(VerifikasiPembayaranService::class);

        // 1. Pembayaran Fitrah Beras Tunai (2 jiwa = 5 kg beras) langsung terverifikasi
        $trxTunai = $pembayaranService->buatTransaksi([
            'mode_muzakki' => 'pilih',
            'muzakki_id' => $muzakki->id,
            'jenis_zakat_id' => $fitrah->id,
            'metode_pembayaran_id' => $tunai->id,
            'bentuk_bayar' => 'beras',
            'anggota_jiwa' => [
                ['nama_anggota' => 'Ahmad', 'hubungan_keluarga' => 'Kepala Keluarga'],
                ['nama_anggota' => 'Siti', 'hubungan_keluarga' => 'Istri'],
            ],
        ]);

        $this->assertEquals(StatusPembayaranEnum::TERVERIFIKASI, $trxTunai->status);
        $this->assertEquals(5.0, (float) $trxTunai->total_beras_kg);
        $this->assertEquals(5.0, (float) MutasiStokBeras::latest('id')->value('saldo_sesudah_kg'));

        // 2. Pembayaran Transfer masuk sebagai Menunggu Verifikasi terlebih dahulu
        $bukti = UploadedFile::fake()->image('bukti-transfer.jpg');
        $trxTransfer = $pembayaranService->buatTransaksi([
            'mode_muzakki' => 'pilih',
            'muzakki_id' => $muzakki->id,
            'jenis_zakat_id' => $fitrah->id,
            'metode_pembayaran_id' => $transfer->id,
            'bentuk_bayar' => 'uang',
            'anggota_jiwa' => [
                ['nama_anggota' => 'Ahmad', 'hubungan_keluarga' => 'Kepala Keluarga'],
            ],
        ], $bukti);

        $this->assertEquals(StatusPembayaranEnum::MENUNGGU_VERIFIKASI, $trxTransfer->status);
        $this->assertEquals(0.0, (float) (KasTransaksi::latest('id')->value('saldo_setelah') ?? 0));

        // 3. Petugas melakukan verifikasi -> masuk Buku Kas Umum (Rp 45.000)
        $verified = $verifikasiService->terima($trxTransfer, $petugas);
        $this->assertEquals(StatusPembayaranEnum::TERVERIFIKASI, $verified->status);
        $this->assertEquals($petugas->id, $verified->verified_by);
        $this->assertEquals(45000.0, (float) KasTransaksi::latest('id')->value('saldo_setelah'));

        // 4. Transaksi terverifikasi tidak dapat dihapus permanen
        $this->expectException(RuntimeException::class);
        $verified->delete();
    }

    public function test_penyaluran_menolak_jika_saldo_tidak_cukup_dan_memotong_saldo_saat_selesai(): void
    {
        $petugas = User::where('role', UserRoleEnum::PETUGAS->value)->firstOrFail();
        $this->actingAs($petugas);

        $asnaf = Asnaf::where('kode', 'FAKIR')->firstOrFail();
        $fitrah = JenisZakat::where('kode', 'ZKT-FITRAH')->firstOrFail();

        $mustahik = Mustahik::create([
            'nama_lengkap' => 'Ibu Aminah',
            'alamat' => 'RT 02 / RW 01',
            'asnaf_id' => $asnaf->id,
            'status_kelayakan' => StatusKelayakanEnum::LAYAK->value,
        ]);

        /** @var PenyaluranService $penyaluranService */
        $penyaluranService = app(PenyaluranService::class);

        // Saldo kas awal Rp 0 -> gagal diselesaikan
        try {
            $penyaluranService->buatPenyaluran([
                'items' => [[
                    'mustahik_id' => $mustahik->id,
                    'asnaf_id' => $asnaf->id,
                    'jenis_zakat_id' => $fitrah->id,
                    'bentuk_salur' => 'uang',
                    'nominal_uang' => 100000,
                ]],
            ], $petugas, true);
            $this->fail('Seharusnya melempar exception karena saldo kas tidak mencukupi.');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('Saldo kas uang tidak mencukupi', $e->getMessage());
        }

        // Tambah penerimaan tunai Rp 90.000 (2 jiwa x Rp 45.000)
        $muzakki = Muzakki::create(['nama_lengkap' => 'Bapak Hasan', 'no_hp' => '0811111111', 'alamat' => 'Jl. Melati']);
        $tunai = MetodePembayaran::where('is_tunai', true)->firstOrFail();
        app(PembayaranService::class)->buatTransaksi([
            'mode_muzakki' => 'pilih',
            'muzakki_id' => $muzakki->id,
            'jenis_zakat_id' => $fitrah->id,
            'metode_pembayaran_id' => $tunai->id,
            'bentuk_bayar' => 'uang',
            'anggota_jiwa' => [
                ['nama_anggota' => 'Hasan'],
                ['nama_anggota' => 'Fatimah'],
            ],
        ]);

        $penyaluran = $penyaluranService->buatPenyaluran([
            'items' => [[
                'mustahik_id' => $mustahik->id,
                'asnaf_id' => $asnaf->id,
                'jenis_zakat_id' => $fitrah->id,
                'bentuk_salur' => 'uang',
                'nominal_uang' => 50000,
            ]],
        ], $petugas, true);

        $this->assertEquals(StatusPenyaluranEnum::SELESAI, $penyaluran->status);
        $this->assertEquals(40000.0, (float) KasTransaksi::latest('id')->value('saldo_setelah'));
    }

    public function test_kalkulator_zakat_mal_menghitung_seluruh_jenis_harta_dengan_benar(): void
    {
        /** @var ZakatCalculatorService $calc */
        $calc = app(ZakatCalculatorService::class);
        $periode = Periode::aktif()->first();

        // 1. Emas & Perak (nisab 85gr * 1.5jt = 127.5jt) -> harta bersih 150jt -> 2.5% = 3.75jt
        $emas = $calc->hitungMal([
            'jenis_harta' => 'emas_perak',
            'nilai_harta_kotor' => 160000000,
            'hutang_jatuh_tempo' => 10000000,
            'harga_emas_per_gram' => 1500000,
            'sudah_haul' => true,
        ], $periode);
        $this->assertTrue($emas['wajib_zakat']);
        $this->assertEquals(3750000.0, $emas['hasil_zakat']);

        // 2. Penghasilan bulanan bruto (nisab bulanan = 127.5jt / 12 = 10.625jt) -> 12jt -> 2.5% = 300rb
        $profesi = $calc->hitungMal([
            'jenis_harta' => 'penghasilan',
            'metode_penghasilan' => 'bruto',
            'periode_penghasilan' => 'bulanan',
            'nilai_harta_kotor' => 12000000,
            'harga_emas_per_gram' => 1500000,
        ], $periode);
        $this->assertTrue($profesi['wajib_zakat']);
        $this->assertEquals(300000.0, $profesi['hasil_zakat']);

        // 3. Pertanian irigasi alami 1000 kg gabah (nisab 653 kg) -> 10% = 100 kg
        $tani = $calc->hitungMal([
            'jenis_harta' => 'pertanian',
            'jenis_panen' => 'gabah',
            'jenis_irigasi' => 'alami',
            'berat_panen_kg' => 1000,
        ], $periode);
        $this->assertTrue($tani['wajib_zakat']);
        $this->assertEquals(100.0, $tani['hasil_zakat_kg']);
    }
}
