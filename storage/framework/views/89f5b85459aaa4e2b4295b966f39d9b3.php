

<?php $__env->startSection('header'); ?>
    Sandbox Data Purge
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Danger Zone','description' => 'Remove all transactional data while keeping user accounts intact.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Danger Zone','description' => 'Remove all transactional data while keeping user accounts intact.']); ?>
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                <p class="font-semibold">Sandbox only</p>
                <p class="mt-2">
                    This will delete loans, applications, approvals, teams, payments, and every record except user accounts and core settings.
                    Use this only to reset your demo data. This action cannot be undone.
                </p>
            </div>

            <div class="mt-5 rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-100 px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Tables to purge (<?php echo e(count($tables)); ?>)
                </div>
                <div class="max-h-64 overflow-y-auto px-6 py-4 text-sm text-slate-600">
                    <ul class="space-y-1">
                        <?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="flex items-center justify-between border-b border-slate-100/60 py-1 last:border-0">
                                <span><?php echo e($table); ?></span>
                                <span class="text-xs text-slate-400"><?php echo e(number_format($approxRecords[$table])); ?> rows</span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>

            <form method="POST" action="<?php echo e(route('sandbox.purge.run')); ?>" class="mt-6 space-y-4">
                <?php echo csrf_field(); ?>
                <label class="flex items-start gap-3 text-sm text-slate-700">
                    <input type="checkbox" name="confirmation" value="1" class="mt-1 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                    <span>I understand this will permanently delete all sandbox data except user accounts.</span>
                </label>
                <button type="submit" class="w-full rounded-2xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-400">
                    Purge Sandbox Data
                </button>
            </form>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48bdbafd2a1d96966a35067215fb52b4)): ?>
<?php $attributes = $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4; ?>
<?php unset($__attributesOriginal48bdbafd2a1d96966a35067215fb52b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48bdbafd2a1d96966a35067215fb52b4)): ?>
<?php $component = $__componentOriginal48bdbafd2a1d96966a35067215fb52b4; ?>
<?php unset($__componentOriginal48bdbafd2a1d96966a35067215fb52b4); ?>
<?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', ['title' => 'Sandbox Data Purge'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/sandbox/purge.blade.php ENDPATH**/ ?>