

<?php $__env->startSection('header'); ?>
    Roles & Permissions
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <p class="text-base font-semibold text-slate-900">Role Directory</p>
                <p class="text-sm text-slate-500">Create roles, assign fine-grained permissions and keep your back-office secure.</p>
            </div>
            <a
                href="<?php echo e(route('admin.roles.create')); ?>"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                New Role
            </a>
        </div>

        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Existing Roles','description' => 'Every role lists how many teammates are assigned and how many permissions are bundled.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Existing Roles','description' => 'Every role lists how many teammates are assigned and how many permissions are bundled.']); ?>
            <div class="grid gap-4 lg:grid-cols-2">
                <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <p class="text-base font-semibold text-slate-900"><?php echo e(\Illuminate\Support\Str::headline($role->name)); ?></p>
                                <p class="text-xs text-slate-500">
                                    <?php echo e($role->description ?? 'No description provided for this role.'); ?>

                                </p>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <span class="rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-600">
                                    <?php echo e($role->permissions_count); ?> Permissions
                                </span>
                                <span class="rounded-full bg-indigo-50 px-3 py-1 font-semibold text-indigo-600">
                                    <?php echo e($role->users_count); ?> Members
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center gap-3">
                            <a href="<?php echo e(route('admin.roles.edit', $role)); ?>" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                                Edit
                            </a>
                            <?php if($role->name !== 'Admin'): ?>
                                <form
                                    method="POST"
                                    action="<?php echo e(route('admin.roles.destroy', $role)); ?>"
                                    class="inline-flex"
                                    x-data
                                    @submit.prevent="Admin.confirmAction({ title: 'Delete Role?', text: 'This action cannot be undone.', icon: 'warning', confirmButtonText: 'Delete' }).then(confirmed => { if (confirmed) $el.submit(); })"
                                >
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="rounded-xl bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-100">
                                        Delete
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-xs font-semibold text-amber-500">Admin role is protected</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-sm text-slate-500">
                        No roles set up yet. Start by creating the Admin, Loan Officer or Collections roles.
                    </div>
                <?php endif; ?>
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


<?php echo $__env->make('layouts.admin', ['title' => 'Roles & Permissions'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/roles/index.blade.php ENDPATH**/ ?>