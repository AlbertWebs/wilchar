

<?php $__env->startSection('header'); ?>
    Create Role
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Role Details','description' => 'Define the role name, add a short description and select the permissions it should control.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Role Details','description' => 'Define the role name, add a short description and select the permissions it should control.']); ?>
            <form action="<?php echo e(route('admin.roles.store')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo $__env->make('admin.roles.partials.form', [
                    'role' => null,
                    'permissions' => $permissions,
                    'rolePermissions' => [],
                    'mode' => 'create',
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="flex items-center justify-end gap-3">
                    <a href="<?php echo e(route('admin.roles.index')); ?>" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        Save Role
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


<?php echo $__env->make('layouts.admin', ['title' => 'Create Role'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/roles/create.blade.php ENDPATH**/ ?>