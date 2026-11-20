<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label',
    'value',
    'sublabel' => null,
    'accent' => 'emerald',
]));

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

foreach (array_filter(([
    'label',
    'value',
    'sublabel' => null,
    'accent' => 'emerald',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $accentStyles = match ($accent) {
        'gold' => 'bg-amber-50 text-[#9A7B16] border-amber-200',
        'amber' => 'bg-orange-50 text-orange-700 border-orange-200',
        'teal' => 'bg-teal-50 text-teal-800 border-teal-200',
        default => 'bg-emerald-50 text-[#1B4D3E] border-emerald-200',
    };
?>

<div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs hover:shadow-sm transition">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500"><?php echo e($label); ?></p>
            <p class="mt-2 text-xl sm:text-2xl font-bold text-slate-900 tracking-tight break-words"><?php echo e($value); ?></p>
            <?php if($sublabel): ?>
                <p class="mt-1.5 text-xs text-slate-500"><?php echo e($sublabel); ?></p>
            <?php endif; ?>
        </div>
        <?php if(isset($icon)): ?>
            <div class="w-10 h-10 rounded-lg border flex items-center justify-center shrink-0 <?php echo e($accentStyles); ?>">
                <?php echo e($icon); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\zakat-app\resources\views/components/stat-card.blade.php ENDPATH**/ ?>