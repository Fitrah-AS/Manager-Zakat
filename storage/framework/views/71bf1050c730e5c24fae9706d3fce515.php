<?php $__env->startSection('title', 'Beranda - Sistem Informasi Pengelolaan Zakat Fitrah & Zakat Mal'); ?>

<?php $__env->startSection('content'); ?>

<section class="bg-gradient-to-br from-[#1B4D3E] via-[#144435] to-[#0F5132] text-white py-14 sm:py-18 border-b-4 border-[#C9A227]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-7 space-y-5">
            <div class="inline-flex items-center gap-2 rounded-lg bg-white/10 border border-[#C9A227]/50 px-3.5 py-1.5 text-xs font-semibold text-[#F2D57E]">
                <span><?php echo e($periodeAktif ? $periodeAktif->nama_periode : 'Periode Zakat Aktif'); ?></span>
                <?php if($periodeAktif?->tahun_hijriah): ?>
                    <span>• <?php echo e($periodeAktif->tahun_hijriah); ?></span>
                <?php endif; ?>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight leading-tight">
                Transparansi Pengelolaan Zakat Fitrah &amp; Zakat Mal
            </h1>
            <p class="text-sm sm:text-base text-emerald-100/90 leading-relaxed max-w-2xl">
                Informasi resmi tarif zakat fitrah, ketentuan nisab &amp; haul zakat mal, batas waktu penerimaan, kalkulator zakat mal, serta statistik penerimaan dan penyaluran secara langsung dari database lembaga.
            </p>
            <div class="flex flex-wrap items-center gap-3 pt-2">
                <a href="<?php echo e(route('kalkulator.index')); ?>" class="rounded-lg bg-[#C9A227] hover:bg-[#B8921F] px-5 py-3 text-sm font-bold text-slate-950 shadow-md transition">
                    Hitung Zakat Mal Sekarang
                </a>
                <a href="#statistik" class="rounded-lg border border-white/30 bg-white/10 hover:bg-white/20 px-5 py-3 text-sm font-semibold text-white transition">
                    Lihat Statistik Penyaluran
                </a>
            </div>
        </div>

        
        <div class="lg:col-span-5">
            <div class="rounded-2xl bg-white text-slate-900 p-6 shadow-xl border border-emerald-900/10 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-[#1B4D3E]">Tarif Zakat Fitrah Periode Aktif</p>
                        <p class="text-xs text-slate-500 mt-0.5"><?php echo e($periodeAktif?->nama_periode ?? 'Belum ada periode aktif'); ?></p>
                    </div>
                    <span class="rounded-md bg-emerald-100 text-emerald-800 px-2.5 py-1 text-[11px] font-bold">Resmi</span>
                </div>

                <?php if($periodeAktif?->tarifFitrah): ?>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-[#F4F6F5] p-4 border border-slate-200">
                            <p class="text-xs text-slate-500">Dalam Beras</p>
                            <p class="text-2xl font-bold text-[#1B4D3E] mt-1"><?php echo e(number_format((float) $periodeAktif->tarifFitrah->beras_per_jiwa_kg, 2, ',', '.')); ?> kg</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">per jiwa</p>
                        </div>
                        <div class="rounded-xl bg-[#F4F6F5] p-4 border border-slate-200">
                            <p class="text-xs text-slate-500">Dalam Uang</p>
                            <p class="text-2xl font-bold text-[#1B4D3E] mt-1">Rp <?php echo e(number_format((float) $periodeAktif->tarifFitrah->nominal_uang, 0, ',', '.')); ?></p>
                            <p class="text-[11px] text-slate-500 mt-0.5">per jiwa</p>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-slate-500">Tarif zakat fitrah periode aktif belum ditetapkan oleh Admin.</p>
                <?php endif; ?>

                <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-xs text-amber-900">
                    <p class="font-bold">Batas Waktu Pembayaran Zakat Fitrah</p>
                    <p class="mt-1">
                        <?php if($periodeAktif?->batas_akhir_fitrah): ?>
                            Maksimal <span class="font-bold"><?php echo e($periodeAktif->batas_akhir_fitrah->format('d F Y, H:i')); ?> WIB</span> (sebelum shalat Idul Fitri).
                        <?php elseif($periodeAktif?->tanggal_selesai): ?>
                            Periode berlangsung hingga <span class="font-bold"><?php echo e($periodeAktif->tanggal_selesai->format('d F Y')); ?></span>.
                        <?php else: ?>
                            Mengikuti jadwal resmi pengumuman lembaga amil zakat.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


<section id="statistik" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900">Statistik Penerimaan &amp; Penyaluran Zakat</h2>
        <p class="text-sm text-slate-600 mt-1">Data transparan dihitung langsung dari transaksi berstatus terverifikasi dan penyaluran selesai pada periode aktif.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
            <p class="text-xs font-medium text-slate-500">Penerimaan Zakat (Uang)</p>
            <p class="text-2xl font-bold text-[#1B4D3E] mt-1">Rp <?php echo e(number_format($totalPenerimaanUang, 0, ',', '.')); ?></p>
            <p class="text-[11px] text-slate-500 mt-1"><?php echo e(number_format($totalTransaksiTerverifikasi, 0, ',', '.')); ?> transaksi terverifikasi</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
            <p class="text-xs font-medium text-slate-500">Penerimaan Zakat (Beras)</p>
            <p class="text-2xl font-bold text-amber-700 mt-1"><?php echo e(number_format($totalPenerimaanBerasKg, 2, ',', '.')); ?> kg</p>
            <p class="text-[11px] text-slate-500 mt-1">Tercatat terpisah dari kas rupiah</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
            <p class="text-xs font-medium text-slate-500">Total Tersalurkan</p>
            <p class="text-lg font-bold text-[#1B4D3E] mt-1">Rp <?php echo e(number_format($totalDisalurkanUang, 0, ',', '.')); ?></p>
            <p class="text-sm font-bold text-amber-700"><?php echo e(number_format($totalDisalurkanBerasKg, 2, ',', '.')); ?> kg beras</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
            <p class="text-xs font-medium text-slate-500">Muzakki &amp; Mustahik Layak</p>
            <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e(number_format($jumlahMuzakki, 0, ',', '.')); ?> <span class="text-xs font-normal text-slate-500">Muzakki</span></p>
            <p class="text-xs font-semibold text-emerald-700 mt-1"><?php echo e(number_format($jumlahMustahikLayak, 0, ',', '.')); ?> Mustahik layak menerima</p>
        </div>
    </div>

    
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="text-base font-bold text-slate-900">Realisasi Penyaluran per Golongan Asnaf</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Golongan Asnaf</th>
                        <th class="px-6 py-3 font-semibold text-center">Penerima Disalurkan</th>
                        <th class="px-6 py-3 font-semibold text-right">Dana Tersalurkan (Rp)</th>
                        <th class="px-6 py-3 font-semibold text-right">Beras Tersalurkan (Kg)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__currentLoopData = $rekapAsnaf; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asnaf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3">
                                <div class="font-semibold text-slate-900"><?php echo e($asnaf['nama']); ?></div>
                                <?php if($asnaf['deskripsi']): ?>
                                    <div class="text-xs text-slate-500"><?php echo e($asnaf['deskripsi']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3 text-center font-medium"><?php echo e($asnaf['jumlah_penerima']); ?></td>
                            <td class="px-6 py-3 text-right font-semibold text-[#1B4D3E]">Rp <?php echo e(number_format($asnaf['total_uang'], 0, ',', '.')); ?></td>
                            <td class="px-6 py-3 text-right font-semibold text-amber-700"><?php echo e(number_format($asnaf['total_beras_kg'], 2, ',', '.')); ?> kg</td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</section>


<section id="fitrah" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-3">
            <span class="inline-flex rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-bold text-[#1B4D3E]">Informasi Zakat Fitrah</span>
            <?php $__empty_1 = true; $__currentLoopData = $informasi->get('fitrah', collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <h3 class="text-lg font-bold text-slate-900"><?php echo e($info->judul); ?></h3>
                <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line"><?php echo e($info->konten); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <h3 class="text-lg font-bold text-slate-900">Ketentuan Zakat Fitrah</h3>
                <p class="text-sm text-slate-600">Wajib ditunaikan sebelum pelaksanaan shalat Idul Fitri sesuai tarif periode berjalan.</p>
            <?php endif; ?>
        </div>

        <div id="mal" class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-3">
            <span class="inline-flex rounded-md bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-800">Informasi Zakat Mal</span>
            <?php $__empty_1 = true; $__currentLoopData = $informasi->get('mal', collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <h3 class="text-lg font-bold text-slate-900"><?php echo e($info->judul); ?></h3>
                <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line"><?php echo e($info->konten); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <h3 class="text-lg font-bold text-slate-900">Ketentuan Zakat Mal</h3>
                <p class="text-sm text-slate-600">Dikenakan atas harta yang mencapai batas nisab dan ketentuan haul sesuai jenis hartanya.</p>
            <?php endif; ?>
            <div class="pt-2">
                <a href="<?php echo e(route('kalkulator.index')); ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#1B4D3E] hover:underline">
                    Gunakan Kalkulator Zakat Mal &rarr;
                </a>
            </div>
        </div>
    </div>
</section>


<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-2">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Ketentuan Nisab &amp; Haul Zakat Mal</h2>
                <p class="text-xs text-slate-500">Setiap jenis harta memiliki dasar nisab, syarat haul, dan kadar zakat masing-masing.</p>
            </div>
            <a href="<?php echo e(route('kalkulator.index')); ?>" class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2 text-xs font-semibold text-white">
                Buka Kalkulator
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Jenis Harta</th>
                        <th class="px-6 py-3 font-semibold">Standar &amp; Basis Nisab</th>
                        <th class="px-6 py-3 font-semibold">Nilai Nisab</th>
                        <th class="px-6 py-3 font-semibold">Kadar</th>
                        <th class="px-6 py-3 font-semibold">Ketentuan Haul</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $nisabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nisab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3.5 font-bold text-slate-900"><?php echo e($nisab->nama_standar); ?></td>
                            <td class="px-6 py-3.5 text-slate-600"><?php echo e($nisab->basis_nisab); ?></td>
                            <td class="px-6 py-3.5 font-semibold text-[#1B4D3E]">
                                <?php if($nisab->jenis_harta === 'pertanian'): ?>
                                    <?php echo e(number_format((float) $nisab->nisab_pertanian_kg, 0, ',', '.')); ?> kg gabah / 520 kg beras
                                <?php else: ?>
                                    Rp <?php echo e(number_format((float) $nisab->nilai_nisab_uang, 0, ',', '.')); ?>

                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3.5 font-semibold"><?php echo e(number_format((float) $nisab->kadar_zakat_persen, 1, ',', '.')); ?>%</td>
                            <td class="px-6 py-3.5 text-slate-600"><?php echo e($nisab->ketentuan_haul); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">Standar nisab aktif belum tersedia.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>


<section id="lembaga" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 shadow-xs">
        <span class="inline-flex rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-bold text-[#1B4D3E] mb-3">Profil Lembaga Amil Zakat</span>
        <?php $__empty_1 = true; $__currentLoopData = $informasi->get('lembaga', collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <h2 class="text-xl font-bold text-slate-900"><?php echo e($info->judul); ?></h2>
            <p class="text-sm text-slate-600 mt-2 leading-relaxed whitespace-pre-line"><?php echo e($info->konten); ?></p>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <h2 class="text-xl font-bold text-slate-900">Lembaga Amil Zakat</h2>
            <p class="text-sm text-slate-600 mt-2">Menghimpun dan menyalurkan dana zakat fitrah maupun zakat mal secara transparan dan amanah.</p>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\zakat-app\resources\views/public/beranda.blade.php ENDPATH**/ ?>