<?php if(session('success')): ?>
    <div data-alert-box class="mb-5 flex items-start justify-between gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 shadow-xs">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span><?php echo e(session('success')); ?></span>
        </div>
        <button type="button" data-dismiss-alert class="text-emerald-700 hover:text-emerald-900 text-xs font-semibold">Tutup</button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div data-alert-box class="mb-5 flex items-start justify-between gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-xs">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <span><?php echo e(session('error')); ?></span>
        </div>
        <button type="button" data-dismiss-alert class="text-red-700 hover:text-red-900 text-xs font-semibold">Tutup</button>
    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div data-alert-box class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-xs">
        <div class="flex items-center justify-between mb-1">
            <span class="font-semibold">Terdapat kesalahan pada input:</span>
            <button type="button" data-dismiss-alert class="text-red-700 hover:text-red-900 text-xs font-semibold">Tutup</button>
        </div>
        <ul class="list-disc list-inside space-y-0.5 text-xs">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\zakat-app\resources\views/components/alert.blade.php ENDPATH**/ ?>