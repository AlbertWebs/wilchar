

<?php $__env->startSection('header'); ?>
    Client Payments
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'STK Push Payments','description' => 'Review Lipa na M-Pesa Online transactions and attach them to loans.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'STK Push Payments','description' => 'Review Lipa na M-Pesa Online transactions and attach them to loans.']); ?>
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Reference</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Client Phone</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Loan</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Attach</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__currentLoopData = $stkPushes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-900"><?php echo e($payment->mpesa_receipt_number ?? '—'); ?></p>
                                    <p class="text-xs text-slate-400"><?php echo e($payment->created_at->diffForHumans()); ?></p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <p><?php echo e($payment->phone_number); ?></p>
                                    <p class="text-xs text-slate-400"><?php echo e($payment->account_reference); ?></p>
                                </td>
                                <td class="px-4 py-3 font-semibold text-emerald-600">
                                    KES <?php echo e(number_format($payment->amount, 2)); ?>

                                    <span class="ml-2 text-xs <?php echo e($payment->status === 'success' ? 'text-emerald-600' : 'text-amber-600'); ?>">
                                        <?php echo e(ucfirst($payment->status)); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <?php if($payment->loan): ?>
                                        Loan #<?php echo e($payment->loan->id); ?> <span class="text-xs text-slate-400">Outstanding: KES <?php echo e(number_format($payment->loan->outstanding_balance, 2)); ?></span>
                                    <?php else: ?>
                                        <span class="text-xs text-amber-500">Unattached</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="<?php echo e(route('payments.attach')); ?>" class="inline-flex items-center gap-2">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="type" value="stk">
                                        <input type="hidden" name="payment_id" value="<?php echo e($payment->id); ?>">
                                        <input
                                            type="number"
                                            name="loan_id"
                                            class="w-28 rounded-xl border-slate-200 text-xs"
                                            placeholder="Loan ID"
                                            value="<?php echo e(old('loan_id')); ?>"
                                            required
                                        >
                                        <button
                                            type="submit"
                                            class="rounded-xl bg-indigo-500 px-3 py-1 text-xs font-semibold text-white hover:bg-indigo-600"
                                        >
                                            Attach
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <?php echo e($stkPushes->links()); ?>

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'C2B Payments','description' => 'Incoming PayBill/BuyGoods transactions matched to loans.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'C2B Payments','description' => 'Incoming PayBill/BuyGoods transactions matched to loans.']); ?>
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Trans ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Payer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Loan</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Attach</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__currentLoopData = $c2bTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-900"><?php echo e($transaction->trans_id); ?></p>
                                    <p class="text-xs text-slate-400"><?php echo e($transaction->trans_time); ?></p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <p><?php echo e($transaction->msisdn); ?></p>
                                    <p class="text-xs text-slate-400"><?php echo e($transaction->full_name); ?></p>
                                </td>
                                <td class="px-4 py-3 font-semibold text-emerald-600">
                                    KES <?php echo e(number_format($transaction->trans_amount, 2)); ?>

                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <?php if($transaction->loan): ?>
                                        Loan #<?php echo e($transaction->loan->id); ?> <span class="text-xs text-slate-400">Outstanding: KES <?php echo e(number_format($transaction->loan->outstanding_balance, 2)); ?></span>
                                    <?php else: ?>
                                        <span class="text-xs text-amber-500">Unattached</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="<?php echo e(route('payments.attach')); ?>" class="inline-flex items-center gap-2">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="type" value="c2b">
                                        <input type="hidden" name="payment_id" value="<?php echo e($transaction->id); ?>">
                                        <input
                                            type="number"
                                            name="loan_id"
                                            class="w-28 rounded-xl border-slate-200 text-xs"
                                            placeholder="Loan ID"
                                            required
                                        >
                                        <button
                                            type="submit"
                                            class="rounded-xl bg-indigo-500 px-3 py-1 text-xs font-semibold text-white hover:bg-indigo-600"
                                        >
                                            Attach
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <?php echo e($c2bTransactions->links()); ?>

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


<?php echo $__env->make('layouts.admin', ['title' => 'Client Payments'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/payments/index.blade.php ENDPATH**/ ?>