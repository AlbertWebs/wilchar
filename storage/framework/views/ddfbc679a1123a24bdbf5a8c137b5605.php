

<?php $__env->startSection('header'); ?>
    Initiate Disbursement
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Disbursement Details','description' => 'Review the loan details and provide disbursement information.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Disbursement Details','description' => 'Review the loan details and provide disbursement information.']); ?>
            <dl class="grid gap-4 md:grid-cols-3 text-sm text-slate-600">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Application</dt>
                    <dd class="mt-1 font-semibold text-slate-900">
                        <?php echo e($loanApplication->application_number); ?>

                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Client</dt>
                    <dd class="mt-1 font-semibold text-slate-900">
                        <?php echo e($loanApplication->client->full_name); ?>

                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Approved Amount</dt>
                    <dd class="mt-1 text-2xl font-semibold text-slate-900">
                        KES <?php echo e(number_format($loanApplication->amount_approved ?? $loanApplication->amount, 2)); ?>

                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Loan Product</dt>
                    <dd class="mt  -1 font-semibold text-slate-900">
                        <?php echo e($loanApplication->loanProduct->name ?? $loanApplication->loan_type); ?>

                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Recipient Phone</dt>
                    <dd class="mt-1 font-semibold text-slate-900">
                        <?php echo e($loanApplication->client->phone ?? '—'); ?>

                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Status</dt>
                    <dd class="mt-1 font-semibold text-slate-900">
                        <?php echo e(ucfirst($loanApplication->status)); ?> · <?php echo e(ucfirst(str_replace('_', ' ', $loanApplication->approval_stage))); ?>

                    </dd>
                </div>
            </dl>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Disbursement Instructions','description' => 'Enter the disbursement amount and recipient M-Pesa number.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Disbursement Instructions','description' => 'Enter the disbursement amount and recipient M-Pesa number.']); ?>
            <form method="POST" action="<?php echo e(route('disbursements.store', $loanApplication)); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-slate-700">Amount to Disburse</label>
                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            step="0.01"
                            min="1"
                            value="<?php echo e(old('amount', $loanApplication->amount_approved ?? $loanApplication->amount)); ?>"
                            class="mt-1 block w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500"
                            required
                        >
                        <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label for="recipient_phone" class="block text-sm font-medium text-slate-700">Recipient M-Pesa Number</label>
                        <input
                            type="text"
                            name="recipient_phone"
                            id="recipient_phone"
                            value="<?php echo e(old('recipient_phone', $loanApplication->client->phone)); ?>"
                            class="mt-1 block w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500"
                            placeholder="2547XXXXXXXX"
                            required
                        >
                        <?php $__errorArgs = ['recipient_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div>
                    <label for="remarks" class="block text-sm font-medium text-slate-700">Remarks (optional)</label>
                    <textarea
                        name="remarks"
                        id="remarks"
                        rows="3"
                        class="mt-1 block w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="Additional details about this disbursement..."
                    ><?php echo e(old('remarks')); ?></textarea>
                    <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="<?php echo e(route('loan-applications.show', $loanApplication)); ?>" class="rounded-lg border border-slate-200 px  -4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-lg bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600"
                    >
                        Proceed to Disburse via M-Pesa
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
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', ['title' => 'Initiate Disbursement'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wilchar\resources\views/admin/disbursements/create.blade.php ENDPATH**/ ?>