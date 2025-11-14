

<?php $__env->startSection('header'); ?>
    Create Client
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Client Profile','description' => 'Capture the borrower’s personal details and business background.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Client Profile','description' => 'Capture the borrower’s personal details and business background.']); ?>
            <form action="<?php echo e(route('clients.store')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>
                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">First Name</label>
                        <input type="text" name="first_name" value="<?php echo e(old('first_name')); ?>" class="mt-1 w-full rounded-xl border-slate-200" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Middle Name</label>
                        <input type="text" name="middle_name" value="<?php echo e(old('middle_name')); ?>" class="mt-1 w-full rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Last Name</label>
                        <input type="text" name="last_name" value="<?php echo e(old('last_name')); ?>" class="mt-1 w-full rounded-xl border-slate-200" required>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">National ID / Passport</label>
                        <input type="text" name="id_number" value="<?php echo e(old('id_number')); ?>" class="mt-1 w-full rounded-xl border-slate-200" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Phone Number</label>
                        <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="2547XXXXXXXX" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email (optional)</label>
                        <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="mt-1 w-full rounded-xl border-slate-200">
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="<?php echo e(old('date_of_birth')); ?>" class="mt-1 w-full rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Gender</label>
                        <select name="gender" class="mt-1 w-full rounded-xl border-slate-200">
                            <option value="">Select</option>
                            <option value="male" <?php if(old('gender') === 'male'): echo 'selected'; endif; ?>>Male</option>
                            <option value="female" <?php if(old('gender') === 'female'): echo 'selected'; endif; ?>>Female</option>
                            <option value="other" <?php if(old('gender') === 'other'): echo 'selected'; endif; ?>>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Nationality</label>
                        <input type="text" name="nationality" value="<?php echo e(old('nationality')); ?>" class="mt-1 w-full rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                        <select name="status" class="mt-1 w-full rounded-xl border-slate-200">
                            <option value="active" <?php if(old('status') === 'active'): echo 'selected'; endif; ?>>Active</option>
                            <option value="inactive" <?php if(old('status') === 'inactive'): echo 'selected'; endif; ?>>Inactive</option>
                            <option value="blacklisted" <?php if(old('status') === 'blacklisted'): echo 'selected'; endif; ?>>Blacklisted</option>
                        </select>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Business Details</p>
                    <div class="mt-4 grid gap-4 md:grid-cols-3">
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Business Name</label>
                            <input type="text" name="business_name" value="<?php echo e(old('business_name')); ?>" class="mt-1 w-full rounded-xl border-slate-200" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Business Type</label>
                            <input type="text" name="business_type" value="<?php echo e(old('business_type')); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="Retail, Farming..." required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Business Location</label>
                            <input type="text" name="location" value="<?php echo e(old('location')); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="Town · Street · Landmark" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Address (optional)</label>
                            <input type="text" name="address" value="<?php echo e(old('address')); ?>" class="mt-1 w-full rounded-xl border-slate-200">
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Employment & Contacts</p>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Occupation</label>
                            <input type="text" name="occupation" value="<?php echo e(old('occupation')); ?>" class="mt-1 w-full rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Employer</label>
                            <input type="text" name="employer" value="<?php echo e(old('employer')); ?>" class="mt-1 w-full rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">M-PESA Phone</label>
                            <input type="text" name="mpesa_phone" value="<?php echo e(old('mpesa_phone')); ?>" class="mt-1 w-full rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Alternate Phone</label>
                            <input type="text" name="alternate_phone" value="<?php echo e(old('alternate_phone')); ?>" class="mt-1 w-full rounded-xl border-slate-200">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="<?php echo e(route('clients.index')); ?>" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        Save Client
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


<?php echo $__env->make('layouts.admin', ['title' => 'Create Client'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/clients/create.blade.php ENDPATH**/ ?>