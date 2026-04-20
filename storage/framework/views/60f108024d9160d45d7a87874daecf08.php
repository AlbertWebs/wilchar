

<?php $__env->startSection('header'); ?>
    Authorize Disbursement
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Disbursement Details','description' => 'Verify the payout before entering the OTP.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Disbursement Details','description' => 'Verify the payout before entering the OTP.']); ?>
            <dl class="grid gap-4 md:grid-cols-3 text-sm text-slate-600">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Application</dt>
                    <dd class="mt-1 font-semibold text-slate-900"><?php echo e($disbursement->loanApplication->application_number); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Client</dt>
                    <dd class="mt-1 font-semibold text-slate-900"><?php echo e($disbursement->loanApplication->client->full_name); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Prepared By</dt>
                    <dd class="mt-1 font-semibold text-slate-900"><?php echo e($disbursement->preparedBy->name ?? 'Finance'); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Amount</dt>
                    <dd class="mt-1 text-2xl font-semibold text-slate-900">KES <?php echo e(number_format($disbursement->amount, 2)); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Recipient Phone</dt>
                    <dd class="mt-1 font-semibold text-slate-900"><?php echo e($disbursement->recipient_phone); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Notes</dt>
                    <dd class="mt-1 text-slate-700"><?php echo e($disbursement->processing_notes ?? '—'); ?></dd>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Enter OTP','description' => 'The OTP was sent to your email (and phone via SMS).']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Enter OTP','description' => 'The OTP was sent to your email (and phone via SMS).']); ?>
            <form method="POST" action="<?php echo e(route('finance-disbursements.confirm.store', $disbursement)); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">One-Time Password</label>
                    <input
                        type="text"
                        name="otp"
                        maxlength="6"
                        class="mt-1 w-full rounded-xl border-slate-200 text-lg tracking-[0.5em] text-center"
                        placeholder="••••••"
                        required
                    >
                </div>
                <p class="text-xs text-slate-400">
                    This code expires <?php echo e(optional($disbursement->otp_expires_at)->diffForHumans() ?? 'soon'); ?>.
                </p>
                <button type="submit" class="w-full rounded-xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-600">
                    Verify OTP & Disburse
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


<?php echo $__env->make('layouts.admin', ['title' => 'Authorize Disbursement'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views\admin\finance\disbursements\confirm.blade.php ENDPATH**/ ?>