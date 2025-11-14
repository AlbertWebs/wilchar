

<?php $__env->startSection('header'); ?>
    Disbursements
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Disbursement Queue','description' => 'Track M-PESA payouts and manual releases.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Disbursement Queue','description' => 'Track M-PESA payouts and manual releases.']); ?>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Loan App</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Client</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php $__empty_1 = true; $__currentLoopData = $disbursements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $disbursement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-4">
                                <p class="font-semibold text-slate-900">#<?php echo e($disbursement->loanApplication->application_number ?? 'N/A'); ?></p>
                                <p class="text-xs text-slate-500"><?php echo e(optional($disbursement->loanApplication)->loan_type); ?></p>
                            </td>
                            <td class="px-4 py-4 text-slate-700">
                                <?php echo e(optional($disbursement->loanApplication?->client)->full_name ?? 'Unknown'); ?>

                                <div class="text-xs text-slate-500"><?php echo e(optional($disbursement->loanApplication?->client)->phone); ?></div>
                            </td>
                            <td class="px-4 py-4 text-slate-700">
                                <span class="font-semibold text-slate-900">KES <?php echo e(number_format($disbursement->amount, 2)); ?></span>
                                <p class="text-xs text-slate-500"><?php echo e(strtoupper($disbursement->method ?? 'manual')); ?></p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                        'bg-emerald-100 text-emerald-700' => $disbursement->status === 'success',
                                        'bg-amber-100 text-amber-700' => $disbursement->status === 'pending',
                                        'bg-rose-100 text-rose-600' => $disbursement->status === 'failed',
                                    ]); ?>"
                                ">
                                    <?php echo e(ucfirst($disbursement->status)); ?>

                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="<?php echo e(route('disbursements.show', $disbursement)); ?>" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">View</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">No disbursements queued.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                <?php echo e($disbursements->links()); ?>

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


<?php echo $__env->make('layouts.admin', ['title' => 'Disbursements'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/disbursements/index.blade.php ENDPATH**/ ?>