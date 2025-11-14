<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'value',
    'icon' => null,
    'trend' => null,
    'trendLabel' => null,
    'bg' => 'bg-white',
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
    'title',
    'value',
    'icon' => null,
    'trend' => null,
    'trendLabel' => null,
    'bg' => 'bg-white',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->class([$bg, 'rounded-2xl border border-slate-200/60 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md'])); ?>>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500"><?php echo e($title); ?></p>
            <p class="mt-3 text-2xl font-semibold text-slate-900"><?php echo e($value); ?></p>
        </div>
        <?php if($icon): ?>
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-100 text-slate-600">
                <?php echo $icon; ?>

            </div>
        <?php endif; ?>
    </div>
    <?php if($trend): ?>
        <div class="mt-4 flex items-center gap-2 text-sm">
            <span class="flex items-center gap-1 font-medium <?php echo e($trend > 0 ? 'text-emerald-600' : 'text-rose-500'); ?>">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <?php if($trend > 0): ?>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12l5-5 5 5M5 17h10" />
                    <?php else: ?>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 12l-5 5-5-5M19 7H9" />
                    <?php endif; ?>
                </svg>
                <?php echo e(number_format($trend, 1)); ?>%
            </span>
            <?php if($trendLabel): ?>
                <span class="text-slate-500"><?php echo e($trendLabel); ?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php /**PATH C:\projects\wilchar\resources\views/components/admin/stat-card.blade.php ENDPATH**/ ?>