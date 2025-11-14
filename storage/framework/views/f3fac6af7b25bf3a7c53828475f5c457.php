

<?php $__env->startSection('header'); ?>
    Shareholders & Contributions
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Add Shareholder']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Add Shareholder']); ?>
            <form
                action="<?php echo e(route('shareholders.store')); ?>"
                method="POST"
                x-ajax="{ successMessage: { title: 'Shareholder Added' }, onSuccess() { window.location.reload(); } }"
                class="grid gap-4 md:grid-cols-3"
            >
                <?php echo csrf_field(); ?>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Name</label>
                    <input type="text" name="name" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</label>
                    <input type="email" name="email" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Phone</label>
                    <input type="text" name="phone" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div class="md:col-span-3">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Address</label>
                    <input type="text" name="address" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Shares Owned</label>
                    <input type="number" name="shares_owned" step="0.01" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div class="md:col-span-3">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Notes</label>
                    <input type="text" name="notes" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div class="md:col-span-3 flex justify-end">
                    <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                        Save Shareholder
                    </button>
                </div>
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

        <div class="grid gap-6 xl:grid-cols-2">
            <?php $__currentLoopData = $shareholders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shareholder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900"><?php echo e($shareholder->name); ?></h3>
                            <p class="text-xs text-slate-500"><?php echo e($shareholder->email); ?> · <?php echo e($shareholder->phone); ?></p>
                        </div>
                        <div class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-600">
                            KES <?php echo e(number_format($shareholder->contributions_total ?? 0, 2)); ?>

                        </div>
                    </div>

                    <div class="mt-4 border-t border-slate-100 pt-4 text-sm text-slate-600">
                        <p>Shares Owned: <span class="font-semibold text-slate-900"><?php echo e(number_format($shareholder->shares_owned ?? 0, 2)); ?></span></p>
                        <p class="text-xs text-slate-500"><?php echo e($shareholder->notes ?? 'No notes provided.'); ?></p>
                    </div>

                    <form
                        action="<?php echo e(route('shareholders.contributions.store', $shareholder)); ?>"
                        method="POST"
                        x-ajax="{ successMessage: { title: 'Contribution Added' }, onSuccess() { window.location.reload(); } }"
                        class="mt-4 grid gap-3 md:grid-cols-3"
                    >
                        <?php echo csrf_field(); ?>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date</label>
                            <input type="date" name="contribution_date" class="mt-1 w-full rounded-xl border-slate-200" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</label>
                            <input type="number" name="amount" step="0.01" class="mt-1 w-full rounded-xl border-slate-200" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Reference</label>
                            <input type="text" name="reference" class="mt-1 w-full rounded-xl border-slate-200">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Description</label>
                            <input type="text" name="description" class="mt-1 w-full rounded-xl border-slate-200">
                        </div>
                        <div class="flex items-end justify-end">
                            <button type="submit" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                Add Contribution
                            </button>
                        </div>
                    </form>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div>
            <?php echo e($shareholders->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', ['title' => 'Shareholders'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/shareholders/index.blade.php ENDPATH**/ ?>