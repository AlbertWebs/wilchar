

<?php $__env->startSection('header'); ?>
    M-Pesa Account Balance
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Current Balance Snapshot','description' => 'Request and review your M-Pesa paybill working, utility and charges account balances.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Current Balance Snapshot','description' => 'Request and review your M-Pesa paybill working, utility and charges account balances.']); ?>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 text-sm text-slate-600">
                    <?php if($latestBalance): ?>
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-500">Last Successful Check</p>
                                <p class="text-sm font-semibold text-slate-900">
                                    <?php echo e($latestBalance->account_balance_time ?? $latestBalance->created_at?->format('Y-m-d H:i')); ?>

                                </p>
                            </div>
                            <div class="h-8 w-px bg-slate-200"></div>
                            <div class="space-y-1 text-xs text-slate-600">
                                <p>Working: <span class="font-semibold text-slate-900">
                                    KES <?php echo e(number_format($latestBalance->working_account_balance ?? 0, 2)); ?>

                                </span></p>
                                <p>Utility: <span class="font-semibold text-slate-900">
                                    KES <?php echo e(number_format($latestBalance->utility_account_balance ?? 0, 2)); ?>

                                </span></p>
                                <p>Charges: <span class="font-semibold text-slate-900">
                                    KES <?php echo e(number_format($latestBalance->charges_paid_account_balance ?? 0, 2)); ?>

                                </span></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-xs text-slate-500">
                            No successful account balance checks recorded yet.
                        </p>
                    <?php endif; ?>
                </div>

                <form method="POST" action="<?php echo e(route('mpesa.account-balance.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M12 4v16m8-8H4"/>
                        </svg>
                        Request New Balance
                    </button>
                </form>
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

        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Balance Request History','description' => 'All account balance queries sent to M-Pesa.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Balance Request History','description' => 'All account balance queries sent to M-Pesa.']); ?>
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Originator Conversation</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Requested By</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Result Code</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Result Description</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $balances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $balance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-900"><?php echo e($balance->originator_conversation_id ?? '—'); ?></p>
                                    <p class="text-xs text-slate-400"><?php echo e($balance->conversation_id ?? 'No callback yet'); ?></p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <?php echo e($balance->requester?->name ?? 'System'); ?>

                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                            <?php if($balance->status === 'success'): ?>
                                                bg-emerald-100 text-emerald-700
                                            <?php elseif($balance->status === 'failed'): ?>
                                                bg-rose-100 text-rose-700
                                            <?php else: ?>
                                                bg-amber-100 text-amber-700
                                            <?php endif; ?>"
                                    >
                                        <?php echo e(ucfirst($balance->status ?? 'pending')); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <?php echo e($balance->result_code ?? '—'); ?>

                                </td>
                                <td class="px-4 py-3 text-xs text-slate-600">
                                    <?php echo e($balance->result_desc ?? '—'); ?>

                                </td>
                                <td class="px-4 py-3 text-right text-xs text-slate-500">
                                    <?php echo e($balance->created_at?->format('Y-m-d H:i')); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">
                                    No account balance requests found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <?php echo e($balances->links()); ?>

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



<?php echo $__env->make('layouts.admin', ['title' => 'M-Pesa Account Balance'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wilchar\resources\views/admin/mpesa/account-balance/index.blade.php ENDPATH**/ ?>