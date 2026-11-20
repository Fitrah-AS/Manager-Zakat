<?php $__env->startSection('title', 'Detail Transaksi ' . $pembayaran->kode_transaksi); ?>
<?php $__env->startSection('page_title', 'Detail Pembayaran: ' . $pembayaran->kode_transaksi); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <a href="<?php echo e(route('pembayaran.index')); ?>" class="text-xs font-semibold text-[#1B4D3E] hover:underline">&larr; Kembali ke Daftar Pembayaran</a>

    <div class="flex flex-wrap items-center gap-2">
        <?php if($pembayaran->status->value === 'menunggu_verifikasi'): ?>
            <a href="<?php echo e(route('verifikasi.show', $pembayaran)); ?>" class="rounded-lg bg-[#C9A227] hover:bg-[#B8921F] px-3.5 py-2 text-xs font-bold text-slate-950">
                Periksa &amp; Verifikasi
            </a>
        <?php endif; ?>
        <a href="<?php echo e(route('pembayaran.cetak', $pembayaran)); ?>" target="_blank" class="rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-3.5 py-2 text-xs font-semibold text-slate-700">
            Cetak Bukti Pembayaran
        </a>
        <a href="<?php echo e(route('pembayaran.pdf', $pembayaran)); ?>" class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-3.5 py-2 text-xs font-semibold text-white">
            Download PDF
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs">
            <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-4 mb-4">
                <div>
                    <p class="text-xs text-slate-500">Kode Transaksi</p>
                    <h2 class="text-xl font-bold text-slate-900"><?php echo e($pembayaran->kode_transaksi); ?></h2>
                    <p class="text-xs text-slate-500 mt-0.5"><?php echo e($pembayaran->tanggal_bayar?->format('d F Y, H:i')); ?> WIB • <?php echo e($pembayaran->periode->nama_periode ?? '-'); ?></p>
                </div>
                <?php if (isset($component)) { $__componentOriginal435aefee4aa6dd7f20df034696ae03b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal435aefee4aa6dd7f20df034696ae03b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge-status','data' => ['status' => $pembayaran->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pembayaran->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal435aefee4aa6dd7f20df034696ae03b9)): ?>
<?php $attributes = $__attributesOriginal435aefee4aa6dd7f20df034696ae03b9; ?>
<?php unset($__attributesOriginal435aefee4aa6dd7f20df034696ae03b9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal435aefee4aa6dd7f20df034696ae03b9)): ?>
<?php $component = $__componentOriginal435aefee4aa6dd7f20df034696ae03b9; ?>
<?php unset($__componentOriginal435aefee4aa6dd7f20df034696ae03b9); ?>
<?php endif; ?>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-slate-500">Muzakki</p>
                    <p class="font-semibold text-slate-900"><?php echo e($pembayaran->muzakki->nama_lengkap ?? '-'); ?></p>
                    <p class="text-xs text-slate-600"><?php echo e($pembayaran->muzakki->no_hp ?? '-'); ?></p>
                    <p class="text-xs text-slate-500"><?php echo e($pembayaran->muzakki->alamat ?? '-'); ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Metode &amp; Petugas</p>
                    <p class="font-semibold text-slate-900"><?php echo e($pembayaran->metodePembayaran->nama_metode ?? '-'); ?></p>
                    <p class="text-xs text-slate-600">Diinput oleh: <?php echo e($pembayaran->petugasInput->name ?? '-'); ?></p>
                    <?php if($pembayaran->verifikator): ?>
                        <p class="text-xs text-emerald-700">Diverifikasi oleh: <?php echo e($pembayaran->verifikator->name); ?> (<?php echo e($pembayaran->verified_at?->format('d/m/Y H:i')); ?>)</p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($pembayaran->alasan_penolakan): ?>
                <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-3 text-xs text-red-800">
                    <span class="font-bold">Alasan Penolakan:</span> <?php echo e($pembayaran->alasan_penolakan); ?>

                </div>
            <?php endif; ?>
        </div>

        
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
            <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Rincian Pembayaran Zakat</h3>

            <?php $__currentLoopData = $pembayaran->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="rounded-lg border border-slate-200 p-4 bg-slate-50/50 space-y-3">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <p class="font-bold text-slate-900"><?php echo e($detail->jenisZakat->nama_jenis ?? '-'); ?></p>
                            <p class="text-xs text-slate-500">Bentuk bayar: <?php echo e($detail->bentuk_bayar->label()); ?></p>
                        </div>
                        <div class="text-right">
                            <?php if($detail->nominal_uang > 0): ?>
                                <p class="text-base font-bold text-[#1B4D3E]">Rp <?php echo e(number_format($detail->nominal_uang, 0, ',', '.')); ?></p>
                            <?php endif; ?>
                            <?php if($detail->jumlah_beras_kg > 0): ?>
                                <p class="text-base font-bold text-amber-700"><?php echo e(number_format($detail->jumlah_beras_kg, 2, ',', '.')); ?> kg beras</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if($detail->jiwa->isNotEmpty()): ?>
                        <div>
                            <p class="text-xs font-semibold text-slate-700 mb-1.5">Daftar Jiwa (<?php echo e($detail->jumlah_jiwa); ?> Orang):</p>
                            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-xs">
                                <?php $__currentLoopData = $detail->jiwa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $jiwa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="rounded bg-white border border-slate-200 px-3 py-1.5">
                                        <?php echo e($idx + 1); ?>. <span class="font-semibold"><?php echo e($jiwa->nama_anggota); ?></span>
                                        <?php if($jiwa->hubungan_keluarga): ?>
                                            <span class="text-slate-500">(<?php echo e($jiwa->hubungan_keluarga); ?>)</span>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if($detail->perhitunganZakatMal): ?>
                        <?php $calc = $detail->perhitunganZakatMal; ?>
                        <div class="rounded-lg bg-white border border-slate-200 p-3 text-xs space-y-1">
                            <p class="font-bold text-[#1B4D3E]">Rincian Perhitungan Zakat Mal (<?php echo e(strtoupper(str_replace('_', ' ', $calc->jenis_harta))); ?>)</p>
                            <p>Nilai Harta Kotor: <span class="font-semibold">Rp <?php echo e(number_format($calc->nilai_harta_kotor, 0, ',', '.')); ?></span></p>
                            <p>Pengurang / Hutang: <span class="font-semibold">Rp <?php echo e(number_format($calc->hutang_jatuh_tempo, 0, ',', '.')); ?></span></p>
                            <p>Harta Bersih: <span class="font-semibold">Rp <?php echo e(number_format($calc->harta_bersih, 0, ',', '.')); ?></span></p>
                            <p class="text-slate-500"><?php echo e($calc->catatan_perhitungan); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4 h-fit">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Bukti Transfer / Dokumen</h3>
        <?php if($pembayaran->bukti_transfer): ?>
            <?php $isPdf = str_ends_with(strtolower($pembayaran->bukti_transfer), '.pdf'); ?>
            <?php if($isPdf): ?>
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-center text-xs text-slate-600">
                    Dokumen bukti berupa file PDF.
                </div>
            <?php else: ?>
                <img src="<?php echo e(route('pembayaran.bukti', $pembayaran)); ?>" alt="Bukti Transfer" class="w-full rounded-lg border border-slate-200 object-contain max-h-80 bg-slate-50">
            <?php endif; ?>
            <a href="<?php echo e(route('pembayaran.bukti', $pembayaran)); ?>" target="_blank" class="block w-full text-center rounded-lg border border-[#1B4D3E] text-[#1B4D3E] hover:bg-emerald-50 px-4 py-2 text-xs font-semibold">
                Buka File Bukti Asli
            </a>
        <?php else: ?>
            <p class="text-xs text-slate-500">Tidak ada lampiran bukti transfer (pembayaran tunai loket).</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\zakat-app\resources\views/pembayaran/show.blade.php ENDPATH**/ ?>