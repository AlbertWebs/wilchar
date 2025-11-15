

<?php $__env->startSection('header'); ?>
    Team Management
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Create Team','description' => 'Group loan and collection officers into teams to streamline assignment.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Create Team','description' => 'Group loan and collection officers into teams to streamline assignment.']); ?>
            <form
                x-ajax="{
                    successMessage: { title: 'Team Created', text: 'Team saved successfully.' },
                    onSuccess() { window.location.reload(); }
                }"
                class="grid gap-4 md:grid-cols-3"
                action="<?php echo e(route('teams.store')); ?>"
                method="POST"
            >
                <?php echo csrf_field(); ?>
                <div class="md:col-span-1">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Team Name</label>
                    <input type="text" name="name" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Description</label>
                    <input type="text" name="description" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div class="md:col-span-3 flex justify-end">
                    <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                        Create Team
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

        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Teams','description' => 'Assign members to teams and manage roles.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Teams','description' => 'Assign members to teams and manage roles.']); ?>
            <div class="space-y-4">
                <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="text-base font-semibold text-slate-900"><?php echo e($team->name); ?></h3>
                                <p class="text-xs text-slate-500"><?php echo e($team->description ?? 'No description provided.'); ?></p>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <span class="rounded-full bg-emerald-100 px-3 py-1 font-semibold text-emerald-600">
                                    <?php echo e($team->loan_officers_count); ?> Loan Officers
                                </span>
                                <span class="rounded-full bg-sky-100 px-3 py-1 font-semibold text-sky-600">
                                    <?php echo e($team->collection_officers_count); ?> Collection Officers
                                </span>
                                <span class="rounded-full bg-amber-100 px-3 py-1 font-semibold text-amber-600">
                                    <?php echo e($team->finance_officers_count); ?> Finance
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-3 xl:grid-cols-3">
                            <?php $__currentLoopData = $team->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                    <div>
                                        <p class="font-semibold text-slate-900"><?php echo e($member->name); ?></p>
                                        <p class="text-xs text-slate-500"><?php echo e(ucfirst(str_replace('_', ' ', $member->pivot->role))); ?></p>
                                    </div>
                                    <form
                                        method="POST"
                                        action="<?php echo e(route('teams.members.remove', [$team, $member])); ?>"
                                        x-data
                                        @submit.prevent="Admin.confirmAction({ title: 'Remove Member?', text: 'This user will be unassigned from the team.', icon: 'warning', confirmButtonText: 'Remove' }).then(confirmed => { if (confirmed) $el.submit(); })"
                                    >
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-xs font-semibold text-rose-500 hover:text-rose-600">Remove</button>
                                    </form>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <form
                            class="mt-4 grid gap-3 md:grid-cols-3"
                            action="<?php echo e(route('teams.members.assign', $team)); ?>"
                            method="POST"
                            x-ajax="{ successMessage: { title: 'Member Added' }, onSuccess() { window.location.reload(); } }"
                        >
                            <?php echo csrf_field(); ?>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">User</label>
                                <select name="user_id" class="mt-1 w-full rounded-xl border-slate-200" required>
                                    <option value="">Select user</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> · <?php echo e($user->email); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Role</label>
                                <select name="role" class="mt-1 w-full rounded-xl border-slate-200" required>
                                    <option value="loan_officer">Loan Officer</option>
                                    <option value="collection_officer">Collection Officer</option>
                                    <option value="finance">Finance</option>
                                    <option value="marketer">Marketer</option>
                                    <option value="recovery_officer">Recovery Officer</option>
                                </select>
                            </div>
                            <div class="flex items-end justify-end">
                                <button type="submit" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                                    Assign
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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


<?php echo $__env->make('layouts.admin', ['title' => 'Teams'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/teams/index.blade.php ENDPATH**/ ?>