<?php $__env->startSection('title', 'Periksa Verifikasi ' . $pembayaran->kode_transaksi); ?>
<?php $__env->startSection('page_title', 'Verifikasi Transaksi: ' . $pembayaran->kode_transaksi); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <a href="<?php echo e(route('verifikasi.index')); ?>" class="text-xs font-semibold text-[#1B4D3E] hover:underline">&larr; Kembali ke Daftar Verifikasi</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs">
            <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-4 mb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900"><?php echo e($pembayaran->kode_transaksi); ?></h2>
                    <p class="text-xs text-slate-500"><?php echo e($pembayaran->tanggal_bayar?->format('d F Y, H:i')); ?> WIB • <?php echo e($pembayaran->periode->nama_periode ?? '-'); ?></p>
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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-5">
                <div>
                    <p class="text-xs text-slate-500">Muzakki</p>
                    <p class="font-semibold text-slate-900"><?php echo e($pembayaran->muzakki->nama_lengkap ?? '-'); ?></p>
                    <p class="text-xs text-slate-600"><?php echo e($pembayaran->muzakki->no_hp ?? '-'); ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Metode &amp; Petugas Input</p>
                    <p class="font-semibold text-slate-900"><?php echo e($pembayaran->metodePembayaran->nama_metode ?? '-'); ?></p>
                    <p class="text-xs text-slate-600">Diinput: <?php echo e($pembayaran->petugasInput->name ?? '-'); ?></p>
                </div>
            </div>

            <?php $__currentLoopData = $pembayaran->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="font-bold text-slate-900"><?php echo e($detail->jenisZakat->nama_jenis ?? '-'); ?></p>
                        <?php if($detail->jumlah_jiwa): ?>
                            <p class="text-xs text-slate-600"><?php echo e($detail->jumlah_jiwa); ?> jiwa: <?php echo e($detail->jiwa->pluck('nama_anggota')->implode(', ')); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="text-right font-bold text-base">
                        <?php if($detail->nominal_uang > 0): ?>
                            <span class="text-[#1B4D3E]">Rp <?php echo e(number_format($detail->nominal_uang, 0, ',', '.')); ?></span>
                        <?php endif; ?>
                        <?php if($detail->jumlah_beras_kg > 0): ?>
                            <span class="text-amber-700"><?php echo e(number_format($detail->jumlah_beras_kg, 2, ',', '.')); ?> kg beras</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if($pembayaran->status->value === 'menunggu_verifikasi'): ?>
                <div class="mt-6 pt-5 border-t border-slate-200 flex flex-wrap items-center gap-3">
                    <form method="POST" action="<?php echo e(route('verifikasi.terima', $pembayaran)); ?>" data-loading-form>
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <button type="submit" data-loading-text="Memverifikasi..." class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-5 py-2.5 text-xs font-bold text-white shadow cursor-pointer">
                            <span data-submit-label>Terima &amp; Verifikasi Pembayaran</span>
                        </button>
                    </form>

                    <button type="button" data-modal-open="modal-tolak-pembayaran" class="rounded-lg border border-red-300 bg-red-50 hover:bg-red-100 px-5 py-2.5 text-xs font-bold text-red-700 cursor-pointer">
                        Tolak Pembayaran
                    </button>
                </div>
            <?php else: ?>
                <div class="mt-5 rounded-lg bg-slate-50 border border-slate-200 p-4 text-xs text-slate-700">
                    <p><span class="font-semibold">Diverifikasi/Diproses oleh:</span> <?php echo e($pembayaran->verifikator->name ?? '-'); ?> (<?php echo e($pembayaran->verified_at?->format('d/m/Y H:i')); ?> WIB)</p>
                    <?php if($pembayaran->alasan_penolakan): ?>
                        <p class="mt-1 text-red-700"><span class="font-semibold">Alasan Penolakan:</span> <?php echo e($pembayaran->alasan_penolakan); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4 h-fit">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Bukti Transfer</h3>
        <?php if($pembayaran->bukti_transfer): ?>
            <?php if(str_ends_with(strtolower($pembayaran->bukti_transfer), '.pdf')): ?>
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-center text-xs text-slate-600">Dokumen PDF terlampir.</div>
            <?php else: ?>
                <img src="<?php echo e(route('pembayaran.bukti', $pembayaran)); ?>" alt="Bukti Transfer" class="w-full rounded-lg border border-slate-200 object-contain max-h-96 bg-slate-50">
            <?php endif; ?>
            <a href="<?php echo e(route('pembayaran.bukti', $pembayaran)); ?>" target="_blank" class="block w-full text-center rounded-lg border border-[#1B4D3E] text-[#1B4D3E] hover:bg-emerald-50 px-4 py-2 text-xs font-semibold">
                Buka Bukti Ukuran Penuh
            </a>
        <?php else: ?>
            <p class="text-xs text-slate-500">Tidak ada file bukti transfer yang dilampirkan.</p>
        <?php endif; ?>
    </div>
</div>


<div id="modal-tolak-pembayaran" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-red-600 text-white flex items-center justify-between">
            <h3 class="text-base font-bold">Tolak Pembayaran</h3>
            <button type="button" data-modal-close class="text-red-100 hover:text-white text-sm cursor-pointer">Tutup</button>
        </div>
        <form method="POST" action="<?php echo e(route('verifikasi.tolak', $pembayaran)); ?>" data-loading-form class="p-6 space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alasan Penolakan *</label>
                <textarea name="alasan_penolakan" rows="3" required placeholder="Contoh: Nominal transfer tidak sesuai atau bukti transfer tidak terbaca." class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" data-modal-close class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer">Batal</button>
                <button type="submit" data-loading-text="Menolak..." class="rounded-lg bg-red-600 hover:bg-red-700 px-4 py-2 text-xs font-semibold text-white cursor-pointer">
                    <span data-submit-label>Konfirmasi Tolak</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\zakat-app\resources\views/verifikasi/show.blade.php ENDPATH**/ ?>