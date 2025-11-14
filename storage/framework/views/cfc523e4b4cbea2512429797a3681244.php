

<?php $__env->startSection('header'); ?>
    Review <?php echo e($loanApplication->application_number); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid gap-6 xl:grid-cols-3">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['class' => 'xl:col-span-2','title' => 'Loan Snapshot','description' => 'Double-check details before approving']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'xl:col-span-2','title' => 'Loan Snapshot','description' => 'Double-check details before approving']); ?>
            <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Client</dt>
                    <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loanApplication->client->full_name); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Stage</dt>
                    <dd class="mt-1 font-semibold text-emerald-600"><?php echo e(ucfirst(str_replace('_', ' ', $loanApplication->approval_stage))); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Amount Requested</dt>
                    <dd class="mt-1 font-semibold text-slate-900">KES <?php echo e(number_format($loanApplication->amount, 2)); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Interest</dt>
                    <dd class="mt-1 font-semibold text-slate-900"><?php echo e(number_format($loanApplication->interest_rate ?? 0, 2)); ?>%</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Business</dt>
                    <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loanApplication->business_type); ?> · <?php echo e($loanApplication->business_location); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Purpose</dt>
                    <dd class="mt-1 text-slate-700"><?php echo e($loanApplication->purpose ?? '—'); ?></dd>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Approval Action','description' => 'Provide comments & approve or reject','class' => 'space-y-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Approval Action','description' => 'Provide comments & approve or reject','class' => 'space-y-5']); ?>
            <form
                x-data="{ stage: '<?php echo e($loanApplication->approval_stage); ?>', confirmApprove(event) { event.preventDefault(); Admin.confirmAction({ title: 'Approve Application?', text: 'This will move the application to the next stage.', confirmButtonText: 'Approve' }).then(confirmed => { if (confirmed) event.target.submit(); }); } }"
                method="POST"
                action="<?php echo e(route('approvals.approve', $loanApplication)); ?>"
                @submit="confirmApprove($event)"
                class="space-y-4"
            >
                <?php echo csrf_field(); ?>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Comment</label>
                    <textarea name="comment" rows="3" class="mt-1 w-full rounded-xl border-slate-200 text-sm"><?php echo e(old('comment')); ?></textarea>
                </div>

                <?php if($loanApplication->approval_stage === 'collection_officer'): ?>
                    <div class="grid gap-3 text-sm md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Amount Approved</label>
                            <input type="number" name="amount_approved" class="mt-1 w-full rounded-xl border-slate-200" min="1000" value="<?php echo e(old('amount_approved', $loanApplication->amount_approved ?? $loanApplication->amount)); ?>" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Interest Rate (%)</label>
                            <input type="number" name="interest_rate" step="0.01" class="mt-1 w-full rounded-xl border-slate-200" value="<?php echo e(old('interest_rate', $loanApplication->interest_rate ?? 12)); ?>" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Duration (Months)</label>
                            <input type="number" name="duration_months" class="mt-1 w-full rounded-xl border-slate-200" value="<?php echo e(old('duration_months', $loanApplication->duration_months ?? 12)); ?>" required>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($loanApplication->approval_stage === 'finance_officer'): ?>
                    <div class="grid gap-3 text-sm md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Amount to Disburse</label>
                            <input type="number" name="amount_approved" class="mt-1 w-full rounded-xl border-slate-200" value="<?php echo e(old('amount_approved', $loanApplication->amount_approved ?? $loanApplication->amount)); ?>" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Processing Fee</label>
                            <input type="number" name="processing_fee" step="0.01" class="mt-1 w-full rounded-xl border-slate-200" value="<?php echo e(old('processing_fee', 0)); ?>">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Disbursement Method</label>
                            <input type="text" name="disbursement_method" class="mt-1 w-full rounded-xl border-slate-200" value="<?php echo e(old('disbursement_method', 'M-PESA B2C')); ?>" required>
                        </div>
                    </div>
                <?php endif; ?>

                <button type="submit" class="w-full rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                    Approve & Continue
                </button>
            </form>

            <form
                method="POST"
                action="<?php echo e(route('approvals.reject', $loanApplication)); ?>"
                class="space-y-3"
                x-data
                @submit.prevent="Admin.confirmAction({ title: 'Reject Application?', text: 'This will mark the application as rejected.', icon: 'error', confirmButtonText: 'Reject' }).then(confirmed => { if(confirmed) $el.submit(); })"
            >
                <?php echo csrf_field(); ?>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rejection Reason</label>
                <textarea name="rejection_reason" rows="3" class="w-full rounded-xl border-slate-200 text-sm" required><?php echo e(old('rejection_reason')); ?></textarea>
                <button type="submit" class="w-full rounded-xl border border-rose-300 bg-white px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">
                    Reject Application
                </button>
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


<?php echo $__env->make('layouts.admin', ['title' => 'Approval · ' . $loanApplication->application_number], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/approvals/show.blade.php ENDPATH**/ ?>