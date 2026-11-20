<?php $__env->startSection('title', 'Detail Muzakki - Sistem Informasi Zakat'); ?>
<?php $__env->startSection('page_title', 'Detail Muzakki'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <a href="<?php echo e(route('muzakki.index')); ?>" class="text-xs font-semibold text-[#1B4D3E] hover:underline">&larr; Kembali ke Daftar Muzakki</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs">
        <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Profil Muzakki</h2>
        <dl class="space-y-3 text-sm">
            <div>
                <dt class="text-xs text-slate-500">Nama Lengkap</dt>
                <dd class="font-semibold text-slate-900"><?php echo e($muzakki->nama_lengkap); ?></dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">NIK</dt>
                <dd class="font-medium text-slate-800"><?php echo e($muzakki->nik ?? '-'); ?></dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">Nomor HP</dt>
                <dd class="font-medium text-slate-800"><?php echo e($muzakki->no_hp); ?></dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">Email</dt>
                <dd class="font-medium text-slate-800"><?php echo e($muzakki->email ?? '-'); ?></dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500">Alamat</dt>
                <dd class="text-slate-700"><?php echo e($muzakki->alamat); ?></dd>
            </div>
        </dl>
    </div>

    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-900">Riwayat Pembayaran Zakat</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Kode &amp; Tanggal</th>
                        <th class="px-5 py-3 font-semibold">Periode &amp; Metode</th>
                        <th class="px-5 py-3 font-semibold">Jumlah</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $muzakki->pembayaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-slate-900"><?php echo e($trx->kode_transaksi); ?></div>
                                <div class="text-xs text-slate-500"><?php echo e($trx->tanggal_bayar?->format('d/m/Y H:i')); ?></div>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-600">
                                <div><?php echo e($trx->periode->nama_periode ?? '-'); ?></div>
                                <div><?php echo e($trx->metodePembayaran->nama_metode ?? '-'); ?></div>
                            </td>
                            <td class="px-5 py-3.5 text-xs font-semibold text-slate-900">
                                <?php if($trx->total_uang > 0): ?>
                                    <div>Rp <?php echo e(number_format($trx->total_uang, 0, ',', '.')); ?></div>
                                <?php endif; ?>
                                <?php if($trx->total_beras_kg > 0): ?>
                                    <div class="text-amber-700"><?php echo e(number_format($trx->total_beras_kg, 2, ',', '.')); ?> kg</div>
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
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-400">Belum ada riwayat transaksi pembayaran zakat.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\zakat-app\resources\views/muzakki/show.blade.php ENDPATH**/ ?>