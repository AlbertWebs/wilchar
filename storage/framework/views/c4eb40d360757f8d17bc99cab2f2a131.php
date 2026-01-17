

<?php $__env->startSection('header'); ?>
    Create Product
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Product Details','description' => 'Add a new loan product or solution to display on the website.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Product Details','description' => 'Add a new loan product or solution to display on the website.']); ?>
            <form action="<?php echo e(route('admin.products.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php if($errors->any()): ?>
                    <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                        <p class="font-semibold">We couldn't save the product yet.</p>
                        <ul class="mt-2 list-disc pl-5 text-xs">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Product Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="mt-1 w-full rounded-xl border-slate-200 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 focus:border-rose-400 focus:ring-rose-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required placeholder="e.g. Business Loans">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-xs text-rose-500"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">URL Slug</label>
                        <input type="text" name="slug" value="<?php echo e(old('slug')); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="Auto-generated from name">
                        <p class="mt-1 text-xs text-slate-500">Leave empty to auto-generate</p>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Short Description</label>
                    <input type="text" name="short_description" value="<?php echo e(old('short_description')); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="Brief description (max 500 characters)" maxlength="500">
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Full Description</label>
                    <textarea name="description" rows="5" class="mt-1 w-full rounded-xl border-slate-200 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 focus:border-rose-400 focus:ring-rose-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Detailed product description..."><?php echo e(old('description')); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-rose-500"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Interest Rate (%)</label>
                        <input type="number" name="interest_rate" value="<?php echo e(old('interest_rate')); ?>" step="0.01" min="0" max="100" class="mt-1 w-full rounded-xl border-slate-200" placeholder="e.g. 7.8">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Loan Duration</label>
                        <input type="text" name="loan_duration" value="<?php echo e(old('loan_duration')); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="e.g. 1-4 Weeks">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Display Order</label>
                        <input type="number" name="display_order" value="<?php echo e(old('display_order', 0)); ?>" min="0" class="mt-1 w-full rounded-xl border-slate-200" placeholder="0">
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Minimum Amount (KES)</label>
                        <input type="number" name="min_amount" value="<?php echo e(old('min_amount')); ?>" step="0.01" min="0" class="mt-1 w-full rounded-xl border-slate-200" placeholder="e.g. 1000">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Maximum Amount (KES)</label>
                        <input type="number" name="max_amount" value="<?php echo e(old('max_amount')); ?>" step="0.01" min="0" class="mt-1 w-full rounded-xl border-slate-200" placeholder="e.g. 100000">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Features</label>
                    <div id="features-container" class="mt-2 space-y-2">
                        <div class="flex gap-2">
                            <input type="text" name="features[]" class="flex-1 rounded-xl border-slate-200" placeholder="Enter a feature (e.g. Quick Approval)">
                            <button type="button" onclick="removeFeature(this)" class="rounded-xl bg-rose-50 px-3 py-2 text-rose-600 hover:bg-rose-100">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="addFeature()" class="mt-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        + Add Feature
                    </button>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Product Icon</label>
                        <input type="file" name="icon" accept="image/*" class="mt-1 w-full rounded-xl border-slate-200">
                        <p class="mt-1 text-xs text-slate-500">Small icon (max 512KB, PNG/SVG recommended)</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Product Image</label>
                        <input type="file" name="image" accept="image/*" class="mt-1 w-full rounded-xl border-slate-200">
                        <p class="mt-1 text-xs text-slate-500">Large image (max 2MB, JPG/PNG)</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?> class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <label for="is_active" class="text-sm font-medium text-slate-700">Active (visible on website)</label>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit" class="rounded-xl bg-emerald-500 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        Create Product
                    </button>
                    <a href="<?php echo e(route('admin.products.index')); ?>" class="rounded-xl border border-slate-300 bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
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

    <script>
        function addFeature() {
            const container = document.getElementById('features-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="features[]" class="flex-1 rounded-xl border-slate-200" placeholder="Enter a feature">
                <button type="button" onclick="removeFeature(this)" class="rounded-xl bg-rose-50 px-3 py-2 text-rose-600 hover:bg-rose-100">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;
            container.appendChild(div);
        }

        function removeFeature(button) {
            button.parentElement.remove();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', ['title' => 'Create Product'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/products/create.blade.php ENDPATH**/ ?>