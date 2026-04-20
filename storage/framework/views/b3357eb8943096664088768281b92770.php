<?php $__env->startSection('header'); ?>
    STK Push Details
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Transaction Overview','description' => 'Details for this STK Push request and its callback from M-Pesa.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Transaction Overview','description' => 'Details for this STK Push request and its callback from M-Pesa.']); ?>
            <div class="grid gap-6 lg:grid-cols-3 text-sm text-slate-700">
                <div class="lg:col-span-2 space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500">Payment</p>
                            <dl class="mt-3 space-y-2">
                                <div class="flex justify-between">
                                    <dt>Amount</dt>
                                    <dd class="font-semibold text-slate-900">
                                        KES <?php echo e(number_format($stkPush->amount, 2)); ?>

                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Phone</dt>
                                    <dd class="font-semibold text-slate-900">
                                        <?php echo e($stkPush->phone_number); ?>

                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Account Reference</dt>
                                    <dd class="font-semibold text-slate-900">
                                        <?php echo e($stkPush->account_reference); ?>

                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Description</dt>
                                    <dd class="text-slate-700">
                                        <?php echo e($stkPush->transaction_desc); ?>

                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500">Status</p>
                            <dl class="mt-3 space-y-2">
                                <div class="flex justify-between">
                                    <dt>Current Status</dt>
                                    <dd>
                                        <span
                                            class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                                <?php if($stkPush->status === 'success'): ?>
                                                    bg-emerald-100 text-emerald-700
                                                <?php elseif($stkPush->status === 'failed'): ?>
                                                    bg-rose-100 text-rose-700
                                                <?php else: ?>
                                                    bg-amber-100 text-amber-700
                                                <?php endif; ?>"
                                        >
                                            <?php echo e(ucfirst($stkPush->status ?? 'pending')); ?>

                                        </span>
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Receipt</dt>
                                    <dd class="font-semibold text-slate-900">
                                        <?php echo e($stkPush->mpesa_receipt_number ?? '—'); ?>

                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Result Code</dt>
                                    <dd><?php echo e($stkPush->result_code ?? '—'); ?></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Result Description</dt>
                                    <dd class="text-right text-xs text-slate-600 max-w-xs">
                                        <?php echo e($stkPush->result_desc ?? '—'); ?>

                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Created At</dt>
                                    <dd><?php echo e($stkPush->created_at?->format('Y-m-d H:i')); ?></dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Linking</p>
                        <dl class="mt-3 space-y-2">
                            <div class="flex justify-between">
                                <dt>Initiated By</dt>
                                <dd>
                                    <?php echo e($stkPush->initiator?->name ?? 'System'); ?>

                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt>Linked Loan</dt>
                                <dd>
                                    <?php if($stkPush->loan): ?>
                                        Loan #<?php echo e($stkPush->loan->id); ?>

                                    <?php else: ?>
                                        <span class="text-xs text-amber-500">Not attached to a loan</span>
                                    <?php endif; ?>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Raw Callback Payload</p>
                        <p class="mt-2 text-xs text-slate-500">
                            Useful for debugging integration issues with M-Pesa STK callbacks.
                        </p>
                        <pre class="mt-3 max-h-80 overflow-auto rounded-lg bg-slate-900 p-3 text-xs text-slate-100">
<?php echo e(json_encode($stkPush->callback_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); ?>

                        </pre>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="<?php echo e(route('mpesa.stk-push.index')); ?>" class="text-xs font-semibold text-slate-500 hover:text-slate-700">
                    &larr; Back to STK Push list
                </a>
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



<?php echo $__env->make('layouts.admin', ['title' => 'STK Push Details'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views\admin\mpesa\stk-push\show.blade.php ENDPATH**/ ?>