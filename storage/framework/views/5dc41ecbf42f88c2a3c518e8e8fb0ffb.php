<?php $__env->startSection('title', 'Transaksi Pembayaran Zakat - Sistem Informasi Zakat'); ?>
<?php $__env->startSection('page_title', 'Daftar Transaksi Pembayaran Zakat'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-200 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <form method="GET" action="<?php echo e(route('pembayaran.index')); ?>" class="flex flex-wrap items-center gap-2.5 flex-1">
            <input
                type="text"
                name="q"
                value="<?php echo e($search); ?>"
                placeholder="Cari kode transaksi / nama muzakki..."
                class="w-full sm:w-64 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1B4D3E] focus:outline-none"
            >
            <select name="periode_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Periode</option>
                <?php $__currentLoopData = $periodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p->id); ?>" <?php if((string) $periodeId === (string) $p->id): echo 'selected'; endif; ?>><?php echo e($p->nama_periode); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="menunggu_verifikasi" <?php if($status === 'menunggu_verifikasi'): echo 'selected'; endif; ?>>Menunggu Verifikasi</option>
                <option value="terverifikasi" <?php if($status === 'terverifikasi'): echo 'selected'; endif; ?>>Terverifikasi</option>
                <option value="ditolak" <?php if($status === 'ditolak'): echo 'selected'; endif; ?>>Ditolak</option>
            </select>
            <button type="submit" class="rounded-lg bg-slate-800 hover:bg-slate-900 px-4 py-2 text-xs font-semibold text-white cursor-pointer">
                Filter
            </button>
        </form>

        <a
            href="<?php echo e(route('pembayaran.create')); ?>"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2.5 text-xs font-semibold text-white shadow-xs"
        >
            <span>+ Catat Pembayaran Zakat</span>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Kode &amp; Tanggal</th>
                    <th class="px-5 py-3 font-semibold">Muzakki</th>
                    <th class="px-5 py-3 font-semibold">Jenis &amp; Metode</th>
                    <th class="px-5 py-3 font-semibold">Nominal / Beras</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $pembayarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3.5">
                            <a href="<?php echo e(route('pembayaran.show', $trx)); ?>" class="font-bold text-[#1B4D3E] hover:underline"><?php echo e($trx->kode_transaksi); ?></a>
                            <div class="text-xs text-slate-500"><?php echo e($trx->tanggal_bayar?->format('d/m/Y H:i')); ?></div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-slate-900"><?php echo e($trx->muzakki->nama_lengkap ?? '-'); ?></div>
                            <div class="text-xs text-slate-500"><?php echo e($trx->muzakki->no_hp ?? '-'); ?></div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-700">
                            <div class="font-semibold"><?php echo e($trx->details->first()?->jenisZakat->nama_jenis ?? '-'); ?></div>
                            <div class="text-slate-500"><?php echo e($trx->metodePembayaran->nama_metode ?? '-'); ?></div>
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
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1.5">
                            <a href="<?php echo e(route('pembayaran.show', $trx)); ?>" class="inline-flex rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700">Detail</a>
                            <a href="<?php echo e(route('pembayaran.cetak', $trx)); ?>" target="_blank" class="inline-flex rounded-lg border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-[#1B4D3E]">Cetak</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-sm text-slate-400">Belum ada transaksi pembayaran zakat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100"><?php echo e($pembayarans->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\zakat-app\resources\views/pembayaran/index.blade.php ENDPATH**/ ?>