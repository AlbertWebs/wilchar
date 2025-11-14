

<?php $__env->startSection('header'); ?>
    Assets Management
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Add Asset','description' => 'Track organisational assets and depreciation.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Add Asset','description' => 'Track organisational assets and depreciation.']); ?>
            <form
                action="<?php echo e(route('assets.store')); ?>"
                method="POST"
                x-ajax="{ successMessage: { title: 'Asset Saved' }, onSuccess() { window.location.reload(); } }"
                class="grid gap-4 md:grid-cols-4"
            >
                <?php echo csrf_field(); ?>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Asset Name</label>
                    <input type="text" name="name" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Category</label>
                    <input type="text" name="category" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Purchase Date</label>
                    <input type="date" name="purchase_date" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Purchase Price</label>
                    <input type="number" name="purchase_price" step="0.01" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Current Value</label>
                    <input type="number" name="current_value" step="0.01" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Depreciation</label>
                    <select name="depreciation_method" class="mt-1 w-full rounded-xl border-slate-200">
                        <option value="straight_line">Straight Line</option>
                        <option value="declining_balance">Declining Balance</option>
                        <option value="none">None</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Useful Life (months)</label>
                    <input type="number" name="useful_life_months" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Team</label>
                    <select name="assigned_team_id" class="mt-1 w-full rounded-xl border-slate-200">
                        <option value="">Unassigned</option>
                        <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($team->id); ?>"><?php echo e($team->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Notes</label>
                    <input type="text" name="notes" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                        Save Asset
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

        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Asset Register']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Asset Register']); ?>
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Asset</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Value</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Depreciation</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Team</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <?php $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-slate-900"><?php echo e($asset->name); ?></p>
                                    <p class="text-xs text-slate-500"><?php echo e($asset->category ?? '—'); ?></p>
                                </td>
                                <td class="px-4 py-4 text-slate-700">
                                    Purchase: <?php echo e(number_format($asset->purchase_price, 2)); ?> <br>
                                    Current: <?php echo e(number_format($asset->current_value, 2)); ?>

                                </td>
                                <td class="px-4 py-4 text-slate-700">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $asset->depreciation_method))); ?> <br>
                                    Monthly: <?php echo e(number_format($asset->monthly_depreciation, 2)); ?>

                                </td>
                                <td class="px-4 py-4 text-slate-700">
                                    <?php echo e($asset->team->name ?? 'Unassigned'); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                    <?php echo e($assets->links()); ?>

                </div>
            </div>
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


<?php echo $__env->make('layouts.admin', ['title' => 'Assets'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/assets/index.blade.php ENDPATH**/ ?>