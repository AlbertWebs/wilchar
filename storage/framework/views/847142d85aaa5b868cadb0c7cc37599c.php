<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => null,
    'description' => null,
    'action' => null,
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
    'title' => null,
    'description' => null,
    'action' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<section <?php echo e($attributes->class('rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm')); ?>>
    <?php if($title || $action): ?>
        <header class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <?php if($title): ?>
                    <h2 class="text-base font-semibold text-slate-900"><?php echo e($title); ?></h2>
                <?php endif; ?>
                <?php if($description): ?>
                    <p class="mt-1 text-sm text-slate-500"><?php echo e($description); ?></p>
                <?php endif; ?>
            </div>
            <?php if($action): ?>
                <div class="flex items-center gap-2">
                    <?php echo e($action); ?>

                </div>
            <?php endif; ?>
        </header>
    <?php endif; ?>

    <div class="pt-5">
        <?php echo e($slot); ?>

    </div>
</section>

<?php /**PATH C:\xampp\htdocs\wilchar\resources\views/components/admin/section.blade.php ENDPATH**/ ?>