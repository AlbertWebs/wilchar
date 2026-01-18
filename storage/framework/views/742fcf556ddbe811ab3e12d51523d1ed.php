<?php $__env->startSection('header'); ?>
    C2B Transactions
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if(session('success')): ?>
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3 text-sm text-slate-600">
                <form action="<?php echo e(route('mpesa.c2b.register-urls')); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                       class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                        </svg>
                        Register C2B URLs
                    </button>
                </form>
                <form method="GET" action="<?php echo e(route('mpesa.c2b.index')); ?>" class="flex flex-wrap items-center gap-2">
                    <select name="status" class="rounded-xl border-slate-200 text-xs">
                        <option value="">Status: All</option>
                        <option value="completed" <?php if(request('status') === 'completed'): echo 'selected'; endif; ?>>Completed</option>
                    </select>
                    <input
                        type="text"
                        name="phone"
                        value="<?php echo e(request('phone')); ?>"
                        placeholder="Phone (MSISDN)"
                        class="w-40 rounded-xl border-slate-200 text-xs"
                    >
                    <input
                        type="text"
                        name="trans_id"
                        value="<?php echo e(request('trans_id')); ?>"
                        placeholder="TransID"
                        class="w-40 rounded-xl border-slate-200 text-xs"
                    >
                    <input
                        type="date"
                        name="start_date"
                        value="<?php echo e(request('start_date')); ?>"
                        class="rounded-xl border-slate-200 text-xs"
                    >
                    <input
                        type="date"
                        name="end_date"
                        value="<?php echo e(request('end_date')); ?>"
                        class="rounded-xl border-slate-200 text-xs"
                    >
                    <button
                        type="submit"
                        class="rounded-xl bg-slate-900 px-3 py-1 text-xs font-semibold text-white hover:bg-slate-800"
                    >
                        Filter
                    </button>
                </form>
            </div>

            <div class="text-xs text-slate-500">
                Total: <span class="font-semibold text-slate-900"><?php echo e(number_format($stats['total'])); ?></span> ·
                Completed: <span class="font-semibold text-emerald-600"><?php echo e(number_format($stats['completed'])); ?></span> ·
                Amount (completed): <span class="font-semibold text-slate-900">KES <?php echo e(number_format($stats['total_amount'], 2)); ?></span>
            </div>
        </div>

        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'C2B Payments','description' => 'Incoming PayBill / Till (BuyGoods) transactions.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'C2B Payments','description' => 'Incoming PayBill / Till (BuyGoods) transactions.']); ?>
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Trans ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Payer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Bill Ref</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Loan</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Time</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-900"><?php echo e($tx->trans_id); ?></p>
                                    <p class="text-xs text-slate-400"><?php echo e($tx->transaction_type); ?></p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <p><?php echo e($tx->msisdn); ?></p>
                                    <p class="text-xs text-slate-400"><?php echo e($tx->full_name); ?></p>
                                </td>
                                <td class="px-4 py-3 font-semibold text-emerald-600">
                                    KES <?php echo e(number_format($tx->trans_amount, 2)); ?>

                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <?php echo e($tx->bill_ref_number ?? '—'); ?>

                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <?php if($tx->loan): ?>
                                        Loan #<?php echo e($tx->loan->id); ?>

                                    <?php else: ?>
                                        <span class="text-xs text-amber-500">Not attached</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-right text-xs text-slate-500">
                                    <?php echo e($tx->trans_time); ?>

                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a
                                        href="<?php echo e(route('mpesa.c2b.show', $tx)); ?>"
                                        class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                                    >
                                        View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">
                                    No C2B transactions found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <?php echo e($transactions->withQueryString()->links()); ?>

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



<?php echo $__env->make('layouts.admin', ['title' => 'C2B Transactions'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/mpesa/c2b/index.blade.php ENDPATH**/ ?>