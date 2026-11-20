<?php $__env->startSection('title', 'Catat Pembayaran Zakat - Sistem Informasi Zakat'); ?>
<?php $__env->startSection('page_title', 'Catat Transaksi Pembayaran Zakat'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <a href="<?php echo e(route('pembayaran.index')); ?>" class="text-xs font-semibold text-[#1B4D3E] hover:underline">&larr; Kembali ke Daftar Pembayaran</a>
</div>

<?php if(! $periodeAktif || ! $periodeAktif->tarifFitrah): ?>
    <div class="rounded-xl border border-amber-300 bg-amber-50 p-5 text-sm text-amber-900">
        Periode zakat aktif atau tarif zakat fitrah belum ditetapkan. Silakan hubungi Admin untuk mengaktifkan periode terlebih dahulu.
    </div>
<?php else: ?>
<form
    id="form-pembayaran-zakat"
    method="POST"
    action="<?php echo e(route('pembayaran.store')); ?>"
    enctype="multipart/form-data"
    data-loading-form
    data-tarif-uang="<?php echo e($periodeAktif->tarifFitrah->nominal_uang); ?>"
    data-tarif-beras="<?php echo e($periodeAktif->tarifFitrah->beras_per_jiwa_kg); ?>"
    class="grid grid-cols-1 lg:grid-cols-3 gap-6"
>
    <?php echo csrf_field(); ?>

    
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-slate-900">1. Identitas Muzakki</h2>
                <div class="flex items-center gap-4 text-xs font-semibold">
                    <label class="inline-flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="mode_muzakki" value="pilih" <?php if(old('mode_muzakki', 'pilih') === 'pilih'): echo 'checked'; endif; ?> class="text-[#1B4D3E]">
                        <span>Pilih Terdaftar</span>
                    </label>
                    <label class="inline-flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="mode_muzakki" value="baru" <?php if(old('mode_muzakki') === 'baru'): echo 'checked'; endif; ?> class="text-[#1B4D3E]">
                        <span>+ Muzakki Baru</span>
                    </label>
                </div>
            </div>

            <div id="box-pilih-muzakki">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Cari &amp; Pilih Muzakki</label>
                <select name="muzakki_id" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
                    <option value="">-- Pilih Muzakki --</option>
                    <?php $__currentLoopData = $muzakkis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($m->id); ?>" <?php if(old('muzakki_id') == $m->id): echo 'selected'; endif; ?>>
                            <?php echo e($m->nama_lengkap); ?> (<?php echo e($m->no_hp); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div id="box-baru-muzakki" class="hidden space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="muzakki_baru_nama" value="<?php echo e(old('muzakki_baru_nama')); ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor HP *</label>
                        <input type="text" name="muzakki_baru_no_hp" value="<?php echo e(old('muzakki_baru_no_hp')); ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">NIK (16 Digit, Opsional)</label>
                        <input type="text" name="muzakki_baru_nik" value="<?php echo e(old('muzakki_baru_nik')); ?>" maxlength="16" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Email (Opsional)</label>
                        <input type="email" name="muzakki_baru_email" value="<?php echo e(old('muzakki_baru_email')); ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap *</label>
                    <textarea name="muzakki_baru_alamat" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"><?php echo e(old('muzakki_baru_alamat')); ?></textarea>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">2. Jenis &amp; Rincian Zakat</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Zakat</label>
                    <select id="jenis-zakat-select" name="jenis_zakat_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
                        <?php $__currentLoopData = $jenisZakats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option
                                value="<?php echo e($jz->id); ?>"
                                data-kategori="<?php echo e($jz->kategori); ?>"
                                data-kode="<?php echo e($jz->kode); ?>"
                                <?php if(old('jenis_zakat_id') == $jz->id): echo 'selected'; endif; ?>
                            >
                                <?php echo e($jz->nama_jenis); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Bentuk Pembayaran</label>
                    <select id="bentuk-bayar-select" name="bentuk_bayar" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
                        <option value="uang" <?php if(old('bentuk_bayar') === 'uang'): echo 'selected'; endif; ?>>Uang (Rupiah)</option>
                        <option value="beras" <?php if(old('bentuk_bayar') === 'beras'): echo 'selected'; endif; ?>>Beras (Kilogram)</option>
                    </select>
                </div>
            </div>

            
            <div id="box-zakat-fitrah" class="pt-3 border-t border-slate-100 space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-bold text-[#1B4D3E]">Daftar Jiwa / Anggota Keluarga (Zakat Fitrah)</h3>
                        <p class="text-xs text-slate-500">
                            Tarif aktif: <?php echo e(number_format($periodeAktif->tarifFitrah->beras_per_jiwa_kg, 2, ',', '.')); ?> kg beras atau Rp <?php echo e(number_format($periodeAktif->tarifFitrah->nominal_uang, 0, ',', '.')); ?> / jiwa.
                            Total: <span id="jumlah-jiwa-display" class="font-bold text-slate-900">1 Jiwa</span>
                        </p>
                    </div>
                    <button type="button" id="btn-tambah-jiwa" class="rounded-lg bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 text-xs font-bold text-[#1B4D3E] cursor-pointer">
                        + Tambah Anggota Keluarga
                    </button>
                </div>

                <div id="daftar-jiwa-container" class="space-y-2">
                    <div data-jiwa-row class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-center bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                        <div class="sm:col-span-6">
                            <input type="text" name="anggota_jiwa[0][nama_anggota]" placeholder="Nama lengkap pembayar / anggota ke-1" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                        <div class="sm:col-span-4">
                            <input type="text" name="anggota_jiwa[0][hubungan_keluarga]" value="Kepala Keluarga" placeholder="Hubungan keluarga" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                        <div class="sm:col-span-2 text-right">
                            <span class="text-[11px] text-slate-400">Wajib min. 1</span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div id="box-zakat-mal" class="hidden pt-3 border-t border-slate-100 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nominal Pembayaran Uang (Rp)</label>
                        <input id="nominal-uang-mal" type="number" step="100" min="0" name="nominal_uang" value="<?php echo e(old('nominal_uang')); ?>" placeholder="Contoh: 2500000" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Beras (Kg) — Khusus Pertanian</label>
                        <input id="jumlah-beras-mal" type="number" step="0.01" min="0" name="jumlah_beras_kg" value="<?php echo e(old('jumlah_beras_kg')); ?>" placeholder="Isi jika bayar beras" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
                    </div>
                </div>

                <label class="inline-flex items-center gap-2 text-xs font-semibold text-[#1B4D3E] cursor-pointer">
                    <input id="gunakan-kalkulator-mal" type="checkbox" name="gunakan_kalkulator_mal" value="1" class="rounded border-slate-300 text-[#1B4D3E]">
                    <span>Gunakan Kalkulator Bantu Zakat Mal &amp; Simpan Riwayat Perhitungan</span>
                </label>

                <div id="box-kalkulator-mal-inputs" class="hidden rounded-xl bg-slate-50 border border-slate-200 p-4 space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Harta</label>
                            <select id="mal-jenis-harta" name="jenis_harta" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                                <option value="emas_perak">Emas &amp; Perak</option>
                                <option value="penghasilan">Penghasilan / Profesi</option>
                                <option value="perdagangan">Perdagangan</option>
                                <option value="tabungan_investasi">Tabungan &amp; Investasi</option>
                                <option value="pertanian">Hasil Pertanian</option>
                            </select>
                        </div>
                        <div data-mal-only="emas_perak,penghasilan,perdagangan,tabungan_investasi">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Harga Emas/gram Acuan (Rp)</label>
                            <input id="mal-harga-emas" type="number" name="harga_emas_per_gram" value="<?php echo e($nisabs->get('emas_perak')?->harga_emas_per_gram ?? 1500000); ?>" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" data-mal-only="emas_perak,penghasilan,perdagangan,tabungan_investasi">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nilai Harta / Penghasilan Kotor (Rp)</label>
                            <input id="mal-harta-kotor" type="number" name="nilai_harta_kotor" value="0" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Hutang Jatuh Tempo / Kebutuhan Pokok (Rp)</label>
                            <input id="mal-hutang" type="number" name="hutang_jatuh_tempo" value="0" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 hidden" data-mal-only="penghasilan">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Basis Penghasilan</label>
                            <select id="mal-metode-penghasilan" name="metode_penghasilan" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                                <option value="neto">Neto (Setelah Pengurang)</option>
                                <option value="bruto">Bruto (Penghasilan Kotor)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Periode Hitung</label>
                            <select id="mal-periode-penghasilan" name="periode_penghasilan" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                                <option value="bulanan">Bulanan</option>
                                <option value="tahunan">Tahunan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 hidden" data-mal-only="pertanian">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Panen</label>
                            <select id="mal-jenis-panen" name="jenis_panen" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                                <option value="gabah">Gabah (Nisab 653 kg)</option>
                                <option value="beras">Beras (Nisab 520 kg)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Irigasi</label>
                            <select id="mal-jenis-irigasi" name="jenis_irigasi" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                                <option value="berbiaya">Berbiaya (5%)</option>
                                <option value="alami">Alami / Hujan (10%)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Berat Panen (Kg)</label>
                            <input id="mal-berat-panen" type="number" step="0.1" name="berat_panen_kg" value="0" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Harga/Kg (Rp, Opsional)</label>
                            <input id="mal-harga-per-kg" type="number" name="harga_per_kg" value="0" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
                        <label data-mal-only="emas_perak,perdagangan,tabungan_investasi" class="inline-flex items-center gap-2 text-xs text-slate-700">
                            <input id="mal-sudah-haul" type="checkbox" name="sudah_haul" value="1" checked class="rounded border-slate-300 text-[#1B4D3E]">
                            <span>Sudah memenuhi haul 1 tahun</span>
                        </label>
                        <button type="button" id="btn-hitung-mal-transaksi" class="rounded-lg bg-[#C9A227] hover:bg-[#B8921F] px-3.5 py-2 text-xs font-bold text-slate-950 cursor-pointer">
                            Hitung &amp; Terapkan Zakat
                        </button>
                    </div>
                    <p id="hasil-hitung-mal-info" class="text-xs font-medium text-[#1B4D3E]"></p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">3. Metode &amp; Bukti Pembayaran</h2>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Bayar</label>
                <input type="datetime-local" name="tanggal_bayar" value="<?php echo e(old('tanggal_bayar', now()->format('Y-m-d\TH:i'))); ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Metode Pembayaran</label>
                <select id="metode-pembayaran-select" name="metode_pembayaran_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
                    <?php $__currentLoopData = $metodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option
                            value="<?php echo e($mt->id); ?>"
                            data-tipe="<?php echo e($mt->tipe); ?>"
                            data-rekening="<?php echo e($mt->no_rekening ? "{$mt->nama_bank} {$mt->no_rekening} a.n. {$mt->atas_nama}" : ($mt->atas_nama ?? '')); ?>"
                            <?php if(old('metode_pembayaran_id') == $mt->id): echo 'selected'; endif; ?>
                        >
                            <?php echo e($mt->nama_metode); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <p id="info-rekening-metode" class="text-[11px] text-slate-500 mt-1.5"></p>
            </div>

            <div id="box-bukti-transfer" class="hidden">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Unggah Bukti Transfer (JPG/PNG/PDF, Maks. 2 MB)</label>
                <input id="input-bukti-transfer" type="file" name="bukti_transfer" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-xs text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-[#1B4D3E]">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan (Opsional)</label>
                <textarea name="catatan" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"><?php echo e(old('catatan')); ?></textarea>
            </div>

            <div class="rounded-xl bg-[#1B4D3E] text-white p-4">
                <p class="text-[11px] uppercase tracking-wider text-emerald-200">Estimasi Total Transaksi</p>
                <p id="ringkasan-total-pembayaran" class="text-base font-bold mt-1">-</p>
            </div>

            <button
                type="submit"
                data-loading-text="Menyimpan Transaksi..."
                class="w-full rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-3 text-sm font-bold text-white shadow cursor-pointer"
            >
                <span data-submit-label>Simpan Pembayaran Zakat</span>
            </button>
        </div>
    </div>
</form>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\zakat-app\resources\views/pembayaran/create.blade.php ENDPATH**/ ?>