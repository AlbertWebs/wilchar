<?php $__env->startSection('header'); ?>
    M-Pesa Transaction Status
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="text-sm text-slate-600">
                Use this page to review previous status queries for specific M-Pesa transactions.
            </div>
            <a
                href="<?php echo e(route('mpesa.transaction-status.create')); ?>"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                New Status Query
            </a>
        </div>

        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Status Queries','description' => 'History of transaction status lookups.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Status Queries','description' => 'History of transaction status lookups.']); ?>
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Transaction ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Receipt</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Requested By</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-900"><?php echo e($status->transaction_id); ?></p>
                                    <p class="text-xs text-slate-400">
                                        Originator: <?php echo e($status->originator_conversation_id ?? '—'); ?>

                                    </p>
                                </td>
                                <td class="px-4 py-3 font-semibold text-emerald-600">
                                    <?php if($status->transaction_amount): ?>
                                        KES <?php echo e(number_format($status->transaction_amount, 2)); ?>

                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <?php echo e($status->receipt_number ?? '—'); ?>

                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                            <?php if($status->status === 'found'): ?>
                                                bg-emerald-100 text-emerald-700
                                            <?php elseif($status->status === 'not_found'): ?>
                                                bg-amber-100 text-amber-700
                                            <?php elseif($status->status === 'failed'): ?>
                                                bg-rose-100 text-rose-700
                                            <?php else: ?>
                                                bg-slate-100 text-slate-700
                                            <?php endif; ?>"
                                    >
                                        <?php echo e(ucfirst(str_replace('_', ' ', $status->status ?? 'pending'))); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <?php echo e($status->requester?->name ?? 'System'); ?>

                                </td>
                                <td class="px-4 py-3 text-right text-xs text-slate-500">
                                    <?php echo e($status->created_at?->format('Y-m-d H:i')); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">
                                    No transaction status queries found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <?php echo e($statuses->links()); ?>

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



<?php echo $__env->make('layouts.admin', ['title' => 'M-Pesa Transaction Status'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/mpesa/transaction-status/index.blade.php ENDPATH**/ ?>