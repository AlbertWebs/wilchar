<?php
    $selectedPermissions = collect(old('permissions', $rolePermissions ?? []))->map(fn($value) => (int) $value)->all();
?>

<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Role Name</label>
            <input
                type="text"
                name="name"
                value="<?php echo e(old('name', $role->name ?? '')); ?>"
                class="mt-1 w-full rounded-xl border-slate-200"
                placeholder="e.g. Loan Officer"
                required
            >
            <p class="mt-1 text-xs text-slate-400">Use descriptive names. They will be slugged automatically.</p>
        </div>
        <div>
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Description (optional)</label>
            <input
                type="text"
                name="description"
                value="<?php echo e(old('description', $role->description ?? '')); ?>"
                class="mt-1 w-full rounded-xl border-slate-200"
                placeholder="Short summary of this role"
            >
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white">
        <div class="border-b border-slate-100 px-5 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-900">Permissions Matrix</p>
                    <p class="text-xs text-slate-500">Check the operations this role should be able to perform.</p>
                </div>
                <label class="flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100 cursor-pointer">
                    <input
                        type="checkbox"
                        id="select-all-permissions"
                        class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                    >
                    <span>Select All</span>
                </label>
            </div>
        </div>
        <div class="divide-y divide-slate-100">
            <?php $__empty_1 = true; $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $modulePermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="px-5 py-4" data-module="<?php echo e($module); ?>">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    class="module-select-all rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                    data-module="<?php echo e($module); ?>"
                                >
                                <p class="text-sm font-semibold text-slate-800"><?php echo e(\Illuminate\Support\Str::headline($module)); ?></p>
                            </label>
                        </div>
                        <span class="text-xs uppercase tracking-wide text-slate-400"><?php echo e(count($modulePermissions)); ?> permission(s)</span>
                    </div>
                    <div class="mt-3 grid gap-2 md:grid-cols-2">
                        <?php $__currentLoopData = $modulePermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-sm text-slate-700">
                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="<?php echo e($permission->id); ?>"
                                    class="permission-checkbox mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                    data-module="<?php echo e($module); ?>"
                                    <?php if(in_array($permission->id, $selectedPermissions, true)): echo 'checked'; endif; ?>
                                >
                                <div>
                                    <span class="font-semibold"><?php echo e(\Illuminate\Support\Str::headline($permission->name)); ?></span>
                                    <?php if($permission->description ?? false): ?>
                                        <p class="text-xs text-slate-500"><?php echo e($permission->description); ?></p>
                                    <?php endif; ?>
                                </div>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="px-5 py-4 text-sm text-slate-500">
                    No permissions configured yet. Seed permissions before creating roles.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Global "Select All" checkbox
            const selectAllCheckbox = document.getElementById('select-all-permissions');
            const moduleSelectAllCheckboxes = document.querySelectorAll('.module-select-all');
            const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

            // Update global "Select All" state based on individual checkboxes
            function updateSelectAllState() {
                const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(permissionCheckboxes).some(cb => cb.checked);
                
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            }

            // Update module "Select All" state
            function updateModuleSelectAllState(module) {
                const moduleCheckboxes = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
                const moduleSelectAll = document.querySelector(`.module-select-all[data-module="${module}"]`);
                
                if (moduleCheckboxes.length === 0) return;
                
                const allChecked = Array.from(moduleCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(moduleCheckboxes).some(cb => cb.checked);
                
                moduleSelectAll.checked = allChecked;
                moduleSelectAll.indeterminate = someChecked && !allChecked;
            }

            // Global "Select All" handler
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    permissionCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    
                    // Update module select all checkboxes
                    moduleSelectAllCheckboxes.forEach(moduleCheckbox => {
                        moduleCheckbox.checked = this.checked;
                        moduleCheckbox.indeterminate = false;
                    });
                });
            }

            // Module "Select All" handlers
            moduleSelectAllCheckboxes.forEach(moduleCheckbox => {
                moduleCheckbox.addEventListener('change', function() {
                    const module = this.dataset.module;
                    const modulePermissionCheckboxes = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
                    
                    modulePermissionCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    
                    updateSelectAllState();
                });
            });

            // Individual permission checkbox handlers
            permissionCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const module = this.dataset.module;
                    updateModuleSelectAllState(module);
                    updateSelectAllState();
                });
            });

            // Initialize states on page load
            moduleSelectAllCheckboxes.forEach(moduleCheckbox => {
                const module = moduleCheckbox.dataset.module;
                updateModuleSelectAllState(module);
            });
            updateSelectAllState();
        });
    </script>
</div>

<?php /**PATH C:\projects\wilchar\resources\views/admin/roles/partials/form.blade.php ENDPATH**/ ?>