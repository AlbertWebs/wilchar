

<?php $__env->startSection('header'); ?>
    Trial Balance
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Generate Trial Balance','description' => 'Summarise debits and credits for a specific period.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Generate Trial Balance','description' => 'Summarise debits and credits for a specific period.']); ?>
        <form
            action="<?php echo e(route('trial-balances.store')); ?>"
            method="POST"
            x-ajax="{ successMessage: { title: 'Trial Balance Generated' }, onSuccess(response) { window.location = response.trial_balance_url ?? '<?php echo e(route('trial-balances.index')); ?>'; } }"
            class="grid gap-4 md:grid-cols-3"
        >
            <?php echo csrf_field(); ?>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Period Start</label>
                <input type="date" name="period_start" class="mt-1 w-full rounded-xl border-slate-200" required>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Period End</label>
                <input type="date" name="period_end" class="mt-1 w-full rounded-xl border-slate-200" required>
            </div>
            <div class="flex items-end justify-end">
                <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                    Generate
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Generated Trial Balances']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Generated Trial Balances']); ?>
        <div class="overflow-hidden rounded-2xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Period</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Totals</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Prepared By</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php $__currentLoopData = $trialBalances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trialBalance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-4 py-4 text-slate-700">
                                <?php echo e($trialBalance->period_start->format('d M Y')); ?> —
                                <?php echo e($trialBalance->period_end->format('d M Y')); ?>

                            </td>
                            <td class="px-4 py-4 text-slate-700">
                                Debits: <?php echo e(number_format($trialBalance->total_debits, 2)); ?> <br>
                                Credits: <?php echo e(number_format($trialBalance->total_credits, 2)); ?>

                            </td>
                            <td class="px-4 py-4 text-slate-700">
                                <?php echo e($trialBalance->preparer->name ?? 'System'); ?>

                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="<?php echo e(route('trial-balances.show', $trialBalance)); ?>" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                        View
                                    </a>
                                    <form method="POST" action="<?php echo e(route('trial-balances.destroy', $trialBalance)); ?>" x-data @submit.prevent="Admin.confirmAction({ title: 'Delete Trial Balance?', icon: 'warning', text: 'Entries will be removed.', confirmButtonText: 'Delete' }).then(confirmed => { if (confirmed) $el.submit(); })">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="rounded-lg border border-rose-200 px-3 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                <?php echo e($trialBalances->links()); ?>

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
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', ['title' => 'Trial Balances'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/trial-balances/index.blade.php ENDPATH**/ ?>