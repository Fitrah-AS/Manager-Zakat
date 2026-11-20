<?php $__env->startSection('title', 'Penyaluran Zakat - Sistem Informasi Zakat'); ?>
<?php $__env->startSection('page_title', 'Daftar Penyaluran Zakat'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-200 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <form method="GET" action="<?php echo e(route('penyaluran.index')); ?>" class="flex flex-wrap items-center gap-2.5 flex-1">
            <select name="periode_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Periode</option>
                <?php $__currentLoopData = $periodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p->id); ?>" <?php if((string) $periodeId === (string) $p->id): echo 'selected'; endif; ?>><?php echo e($p->nama_periode); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="direncanakan" <?php if($status === 'direncanakan'): echo 'selected'; endif; ?>>Direncanakan</option>
                <option value="selesai" <?php if($status === 'selesai'): echo 'selected'; endif; ?>>Selesai</option>
                <option value="dibatalkan" <?php if($status === 'dibatalkan'): echo 'selected'; endif; ?>>Dibatalkan</option>
            </select>
            <button type="submit" class="rounded-lg bg-slate-800 hover:bg-slate-900 px-4 py-2 text-xs font-semibold text-white cursor-pointer">
                Filter
            </button>
        </form>

        <a href="<?php echo e(route('penyaluran.create')); ?>" class="inline-flex items-center justify-center rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2.5 text-xs font-semibold text-white shadow-xs">
            + Buat Penyaluran Zakat
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Kode &amp; Tanggal</th>
                    <th class="px-5 py-3 font-semibold">Petugas</th>
                    <th class="px-5 py-3 font-semibold">Penerima &amp; Total</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $penyalurans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $totalUang = $item->details->where('bentuk_salur.value', 'uang')->sum('nominal_uang');
                        $totalBeras = $item->details->where('bentuk_salur.value', 'beras')->sum('jumlah_beras_kg');
                    ?>
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3.5">
                            <a href="<?php echo e(route('penyaluran.show', $item)); ?>" class="font-bold text-[#1B4D3E] hover:underline"><?php echo e($item->kode_penyaluran); ?></a>
                            <div class="text-xs text-slate-500"><?php echo e($item->tanggal_penyaluran?->format('d/m/Y H:i')); ?></div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-700">
                            <div class="font-semibold"><?php echo e($item->petugas->name ?? '-'); ?></div>
                            <div class="text-slate-400"><?php echo e($item->periode->tahun_hijriah ?? '-'); ?></div>
                        </td>
                        <td class="px-5 py-3.5 text-xs">
                            <div class="text-slate-500"><?php echo e($item->details_count); ?> mustahik</div>
                            <?php if($totalUang > 0): ?>
                                <div class="font-bold text-slate-900">Rp <?php echo e(number_format($totalUang, 0, ',', '.')); ?></div>
                            <?php endif; ?>
                            <?php if($totalBeras > 0): ?>
                                <div class="font-bold text-amber-700"><?php echo e(number_format($totalBeras, 2, ',', '.')); ?> kg beras</div>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3.5">
                            <?php if (isset($component)) { $__componentOriginal435aefee4aa6dd7f20df034696ae03b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal435aefee4aa6dd7f20df034696ae03b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge-status','data' => ['status' => $item->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->status)]); ?>
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
                            <a href="<?php echo e(route('penyaluran.show', $item)); ?>" class="inline-flex rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700">Detail</a>
                            <a href="<?php echo e(route('penyaluran.cetak', $item)); ?>" target="_blank" class="inline-flex rounded-lg border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-[#1B4D3E]">Cetak</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-400">Belum ada data penyaluran zakat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100"><?php echo e($penyalurans->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\zakat-app\resources\views/penyaluran/index.blade.php ENDPATH**/ ?>