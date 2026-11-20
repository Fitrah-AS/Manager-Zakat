<?php $__env->startSection('title', 'Detail Penyaluran ' . $penyaluran->kode_penyaluran); ?>
<?php $__env->startSection('page_title', 'Detail Penyaluran: ' . $penyaluran->kode_penyaluran); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <a href="<?php echo e(route('penyaluran.index')); ?>" class="text-xs font-semibold text-[#1B4D3E] hover:underline">&larr; Kembali ke Daftar Penyaluran</a>

    <div class="flex flex-wrap items-center gap-2">
        <a href="<?php echo e(route('penyaluran.cetak', $penyaluran)); ?>" target="_blank" class="rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-3.5 py-2 text-xs font-semibold text-slate-700">
            Cetak Bukti Penyaluran
        </a>
        <a href="<?php echo e(route('penyaluran.pdf', $penyaluran)); ?>" class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-3.5 py-2 text-xs font-semibold text-white">
            Download PDF
        </a>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs mb-6">
    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-4 mb-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900"><?php echo e($penyaluran->kode_penyaluran); ?></h2>
            <p class="text-xs text-slate-500">
                Tanggal: <?php echo e($penyaluran->tanggal_penyaluran?->format('d F Y, H:i')); ?> WIB •
                Periode: <?php echo e($penyaluran->periode->nama_periode ?? '-'); ?> •
                Petugas: <?php echo e($penyaluran->petugas->name ?? '-'); ?>

            </p>
            <?php if($penyaluran->keterangan): ?>
                <p class="text-xs text-slate-600 mt-1"><?php echo e($penyaluran->keterangan); ?></p>
            <?php endif; ?>
        </div>
        <?php if (isset($component)) { $__componentOriginal435aefee4aa6dd7f20df034696ae03b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal435aefee4aa6dd7f20df034696ae03b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge-status','data' => ['status' => $penyaluran->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($penyaluran->status)]); ?>
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

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 font-semibold">Mustahik</th>
                    <th class="px-4 py-3 font-semibold">Asnaf</th>
                    <th class="px-4 py-3 font-semibold">Jenis Zakat</th>
                    <th class="px-4 py-3 font-semibold">Bentuk</th>
                    <th class="px-4 py-3 font-semibold text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__currentLoopData = $penyaluran->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-slate-900"><?php echo e($d->mustahik->nama_lengkap ?? '-'); ?></div>
                            <div class="text-xs text-slate-500"><?php echo e($d->mustahik->alamat ?? '-'); ?></div>
                        </td>
                        <td class="px-4 py-3 text-xs font-semibold text-slate-700"><?php echo e($d->asnaf->nama_asnaf ?? '-'); ?></td>
                        <td class="px-4 py-3 text-xs text-slate-600"><?php echo e($d->jenisZakat->nama_jenis ?? '-'); ?></td>
                        <td class="px-4 py-3 text-xs"><?php echo e($d->bentuk_salur->label()); ?></td>
                        <td class="px-4 py-3 text-right font-bold">
                            <?php if($d->bentuk_salur->value === 'uang'): ?>
                                <span class="text-[#1B4D3E]">Rp <?php echo e(number_format($d->nominal_uang, 0, ',', '.')); ?></span>
                            <?php else: ?>
                                <span class="text-amber-700"><?php echo e(number_format($d->jumlah_beras_kg, 2, ',', '.')); ?> kg</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <?php if($penyaluran->status->value === 'direncanakan'): ?>
        <div class="mt-6 pt-5 border-t border-slate-200 flex flex-wrap items-center gap-3">
            <form method="POST" action="<?php echo e(route('penyaluran.selesai', $penyaluran)); ?>" data-loading-form>
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <button type="submit" data-loading-text="Memproses..." class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-5 py-2.5 text-xs font-bold text-white shadow cursor-pointer">
                    <span data-submit-label>Selesaikan Penyaluran (Potong Kas / Stok Beras)</span>
                </button>
            </form>

            <form method="POST" action="<?php echo e(route('penyaluran.batal', $penyaluran)); ?>" data-loading-form>
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <button type="submit" data-loading-text="Membatalkan..." class="rounded-lg border border-red-300 bg-red-50 hover:bg-red-100 px-4 py-2.5 text-xs font-bold text-red-700 cursor-pointer">
                    <span data-submit-label>Batalkan Rencana</span>
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\zakat-app\resources\views/penyaluran/show.blade.php ENDPATH**/ ?>