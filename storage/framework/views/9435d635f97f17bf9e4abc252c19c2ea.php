<?php $__env->startSection('header'); ?>
    New Transaction Status Query
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Query Transaction Status','description' => 'Check the status of a specific M-Pesa transaction by Transaction ID or receipt number.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Query Transaction Status','description' => 'Check the status of a specific M-Pesa transaction by Transaction ID or receipt number.']); ?>
            <form method="POST" action="<?php echo e(route('mpesa.transaction-status.store')); ?>" class="space-y-5 max-w-xl">
                <?php echo csrf_field(); ?>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Transaction ID / Receipt Number
                    </label>
                    <input
                        type="text"
                        name="transaction_id"
                        value="<?php echo e(old('transaction_id')); ?>"
                        placeholder="e.g. LHG3Q7YJ5T"
                        class="mt-1 w-full rounded-xl border-slate-200 text-sm"
                        required
                    >
                    <?php $__errorArgs = ['transaction_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="mt-1 text-xs text-slate-500">
                        Use the M-Pesa receipt number (e.g. LHG3Q7YJ5T) or transaction ID you received.
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Remarks (optional)
                    </label>
                    <input
                        type="text"
                        name="remarks"
                        value="<?php echo e(old('remarks')); ?>"
                        placeholder="Short description e.g. Confirm failed B2C payout"
                        class="mt-1 w-full rounded-xl border-slate-200 text-sm"
                    >
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

                <div class="pt-3 flex items-center gap-3">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                        Submit Query
                    </button>

                    <a href="<?php echo e(route('mpesa.transaction-status.index')); ?>" class="text-xs font-semibold text-slate-500 hover:text-slate-700">
                        Cancel
                    </a>
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



<?php echo $__env->make('layouts.admin', ['title' => 'New Transaction Status Query'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/mpesa/transaction-status/create.blade.php ENDPATH**/ ?>