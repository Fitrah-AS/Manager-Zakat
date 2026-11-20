<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['status']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $value = is_object($status) && property_exists($status, 'value') ? $status->value : (string) $status;
    $label = is_object($status) && method_exists($status, 'label') ? $status->label() : ucwords(str_replace('_', ' ', $value));

    $classes = match ($value) {
        'terverifikasi', 'selesai', 'layak' => 'bg-emerald-100 text-[#0F5132] border-emerald-200',
        'menunggu_verifikasi', 'direncanakan', 'menunggu_survei', 'perlu_tinjauan' => 'bg-amber-100 text-amber-800 border-amber-200',
        'ditolak', 'dibatalkan', 'tidak_layak' => 'bg-red-100 text-red-700 border-red-200',
        default => 'bg-slate-100 text-slate-700 border-slate-200',
    };
?>

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border <?php echo e($classes); ?>">
    <?php echo e($label); ?>

</span>
<?php /**PATH C:\laragon\www\zakat-app\resources\views/components/badge-status.blade.php ENDPATH**/ ?>