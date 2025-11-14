

<?php $__env->startSection('header'); ?>
    Loan Products
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <p class="text-base font-semibold text-slate-900">Product Catalogue</p>
                <p class="text-sm text-slate-500">Manage every lending product and tune interest, fees and repayment terms.</p>
            </div>
            <a
                href="<?php echo e(route('loan-products.create')); ?>"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                New Product
            </a>
        </div>

        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Active Products','description' => 'All lending configurations currently available to the team.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Active Products','description' => 'All lending configurations currently available to the team.']); ?>
            <div class="grid gap-4 lg:grid-cols-2">
                <?php $__empty_1 = true; $__currentLoopData = $loanProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="text-base font-semibold text-slate-900"><?php echo e($product->name); ?></p>
                                <p class="text-xs text-slate-500"><?php echo e($product->description ?? 'No description provided.'); ?></p>
                            </div>
                            <span class="rounded-full <?php echo e($product->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500'); ?> px-3 py-1 text-xs font-semibold">
                                <?php echo e($product->is_active ? 'Active' : 'Disabled'); ?>

                            </span>
                        </div>

                        <dl class="mt-4 grid gap-3 text-sm text-slate-600 md:grid-cols-2">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-400">Base Interest</dt>
                                <dd class="text-base font-semibold text-slate-900"><?php echo e(number_format($product->base_interest_rate, 2)); ?>%</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-400">Monthly Rate</dt>
                                <dd class="text-base font-semibold text-indigo-600"><?php echo e(number_format($product->interest_rate_per_month, 2)); ?>%</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-400">Processing Fee</dt>
                                <dd class="text-base font-semibold text-amber-600"><?php echo e(number_format($product->processing_fee_rate, 2)); ?>%</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-400">Tenure</dt>
                                <dd><?php echo e($product->min_duration_months); ?> - <?php echo e($product->max_duration_months); ?> months</dd>
                            </div>
                        </dl>

                        <div class="mt-4 flex items-center gap-2">
                            <a href="<?php echo e(route('loan-products.edit', $product)); ?>" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                                Edit
                            </a>
                            <form
                                action="<?php echo e(route('loan-products.destroy', $product)); ?>"
                                method="POST"
                                class="inline-flex"
                                x-data
                                @submit.prevent="Admin.confirmAction({ title: 'Delete Product?', text: 'This cannot be undone.', icon: 'warning', confirmButtonText: 'Delete' }).then(confirmed => { if (confirmed) $el.submit(); })"
                            >
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="rounded-xl bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-100">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-sm text-slate-500">
                        No loan products configured yet. Create the first product to start lending.
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-4">
                <?php echo e($loanProducts->links()); ?>

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


<?php echo $__env->make('layouts.admin', ['title' => 'Loan Products'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/loan-products/index.blade.php ENDPATH**/ ?>