

<?php $__env->startSection('header'); ?>
    Edit User
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Update Account','description' => 'Change user details and adjust their role assignments.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Update Account','description' => 'Change user details and adjust their role assignments.']); ?>
            <form action="<?php echo e(route('users.update', $user)); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Full Name</label>
                        <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" class="mt-1 w-full rounded-xl border-slate-200" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</label>
                        <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" class="mt-1 w-full rounded-xl border-slate-200" required>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Phone Number</label>
                        <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="2547XXXXXXXX">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">New Password (optional)</label>
                        <input type="password" name="password" class="mt-1 w-full rounded-xl border-slate-200" placeholder="Leave blank to keep current">
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="mt-1 w-full rounded-xl border-slate-200" placeholder="Confirm new password">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Assign Roles</label>
                        <select name="roles[]" multiple class="mt-1 w-full rounded-xl border-slate-200">
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->id); ?>" <?php if(collect(old('roles', $userRoleIds))->contains($role->id)): echo 'selected'; endif; ?>>
                                    <?php echo e(\Illuminate\Support\Str::headline($role->name)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="mt-1 text-xs text-slate-400">Hold CTRL / CMD to select multiple roles.</p>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <a href="<?php echo e(route('users.index')); ?>" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                        ← Back to users
                    </a>
                    <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        Update User
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


<?php echo $__env->make('layouts.admin', ['title' => 'Edit User'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>