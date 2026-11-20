<?php $__env->startSection('title', 'Verifikasi Pembayaran - Sistem Informasi Zakat'); ?>
<?php $__env->startSection('page_title', 'Verifikasi Pembayaran Zakat'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-5 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-amber-200 bg-amber-50 px-5 py-3.5 text-sm text-amber-900">
    <div>
        <span class="font-bold"><?php echo e($jumlahMenunggu); ?> transaksi</span> menunggu verifikasi oleh petugas/amil yang bertugas.
    </div>
    <div class="flex flex-wrap gap-2 text-xs font-semibold">
        <a href="<?php echo e(route('verifikasi.index', ['status' => 'menunggu_verifikasi'])); ?>" class="px-3 py-1.5 rounded-lg <?php echo e($status === 'menunggu_verifikasi' ? 'bg-[#1B4D3E] text-white' : 'bg-white text-slate-700 border border-slate-200'); ?>">Menunggu</a>
        <a href="<?php echo e(route('verifikasi.index', ['status' => 'terverifikasi'])); ?>" class="px-3 py-1.5 rounded-lg <?php echo e($status === 'terverifikasi' ? 'bg-[#1B4D3E] text-white' : 'bg-white text-slate-700 border border-slate-200'); ?>">Terverifikasi</a>
        <a href="<?php echo e(route('verifikasi.index', ['status' => 'ditolak'])); ?>" class="px-3 py-1.5 rounded-lg <?php echo e($status === 'ditolak' ? 'bg-[#1B4D3E] text-white' : 'bg-white text-slate-700 border border-slate-200'); ?>">Ditolak</a>
        <a href="<?php echo e(route('verifikasi.index', ['status' => 'semua'])); ?>" class="px-3 py-1.5 rounded-lg <?php echo e($status === 'semua' ? 'bg-[#1B4D3E] text-white' : 'bg-white text-slate-700 border border-slate-200'); ?>">Semua</a>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Kode &amp; Tanggal</th>
                    <th class="px-5 py-3 font-semibold">Muzakki</th>
                    <th class="px-5 py-3 font-semibold">Metode &amp; Bukti</th>
                    <th class="px-5 py-3 font-semibold">Jumlah</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $pembayarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3.5">
                            <a href="<?php echo e(route('verifikasi.show', $trx)); ?>" class="font-bold text-[#1B4D3E] hover:underline"><?php echo e($trx->kode_transaksi); ?></a>
                            <div class="text-xs text-slate-500"><?php echo e($trx->tanggal_bayar?->format('d/m/Y H:i')); ?></div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-slate-900"><?php echo e($trx->muzakki->nama_lengkap ?? '-'); ?></div>
                            <div class="text-xs text-slate-500"><?php echo e($trx->details->first()?->jenisZakat->nama_jenis ?? '-'); ?></div>
                        </td>
                        <td class="px-5 py-3.5 text-xs">
                            <div class="font-medium text-slate-800"><?php echo e($trx->metodePembayaran->nama_metode ?? '-'); ?></div>
                            <?php if($trx->bukti_transfer): ?>
                                <a href="<?php echo e(route('pembayaran.bukti', $trx)); ?>" target="_blank" class="text-[#1B4D3E] font-semibold hover:underline">Lihat Bukti &rarr;</a>
                            <?php else: ?>
                                <span class="text-slate-400">Tanpa lampiran</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3.5 text-xs font-bold text-slate-900">
                            <?php if($trx->total_uang > 0): ?>
                                <div>Rp <?php echo e(number_format($trx->total_uang, 0, ',', '.')); ?></div>
                            <?php endif; ?>
                            <?php if($trx->total_beras_kg > 0): ?>
                                <div class="text-amber-700"><?php echo e(number_format($trx->total_beras_kg, 2, ',', '.')); ?> kg beras</div>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3.5">
                            <?php if (isset($component)) { $__componentOriginal435aefee4aa6dd7f20df034696ae03b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal435aefee4aa6dd7f20df034696ae03b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge-status','data' => ['status' => $trx->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($trx->status)]); ?>
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
                            <?php if($trx->verifikator): ?>
                                <div class="text-[11px] text-slate-400 mt-1">Oleh: <?php echo e($trx->verifikator->name); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <a href="<?php echo e(route('verifikasi.show', $trx)); ?>" class="inline-flex rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-3.5 py-1.5 text-xs font-semibold text-white">
                                Periksa &amp; Verifikasi
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-400">Tidak ada transaksi pada filter ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100"><?php echo e($pembayarans->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\zakat-app\resources\views/verifikasi/index.blade.php ENDPATH**/ ?>