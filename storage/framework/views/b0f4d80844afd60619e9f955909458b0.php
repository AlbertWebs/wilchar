<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'value',
    'icon' => null,
    'trend' => null,
    'trendLabel' => null,
    'bg' => 'bg-white',
    'textColor' => 'text-slate-900',
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
    'textColor' => 'text-slate-900',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $isGradient = str_contains($bg, 'gradient');
    $titleColor = $isGradient ? 'text-white/90' : 'text-slate-500';
    $valueColor = $isGradient ? 'text-white' : $textColor;
    $trendLabelColor = $isGradient ? 'text-white/70' : 'text-slate-500';
?>

<div <?php echo e($attributes->class([$bg, 'rounded-2xl border border-slate-200/60 p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl'])); ?>>
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-xs font-medium uppercase tracking-wide <?php echo e($titleColor); ?>"><?php echo e($title); ?></p>
            <p class="mt-3 text-2xl font-bold <?php echo e($valueColor); ?>"><?php echo e($value); ?></p>
        </div>
        <?php if(isset($icon)): ?>
            <?php echo $icon; ?>

        <?php endif; ?>
    </div>
    <?php if($trend): ?>
        <div class="mt-4 flex items-center gap-2 text-sm">
            <span class="flex items-center gap-1 font-semibold <?php echo e($trend > 0 ? ($isGradient ? 'text-white' : 'text-emerald-600') : ($isGradient ? 'text-white/80' : 'text-rose-500')); ?>">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <?php if($trend > 0): ?>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12l5-5 5 5M5 17h10" />
                    <?php else: ?>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12l-5 5-5-5M19 7H9" />
                    <?php endif; ?>
                </svg>
                <?php echo e(number_format($trend, 1)); ?>%
            </span>
            <?php if($trendLabel): ?>
                <span class="<?php echo e($trendLabelColor); ?>"><?php echo e($trendLabel); ?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php /**PATH C:\projects\wilchar\resources\views/components/admin/stat-card.blade.php ENDPATH**/ ?>