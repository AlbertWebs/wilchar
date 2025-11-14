

<?php $__env->startSection('header'); ?>
    Liability Management
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Add Liability']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Add Liability']); ?>
            <form
                action="<?php echo e(route('liabilities.store')); ?>"
                method="POST"
                x-ajax="{ successMessage: { title: 'Liability Added' }, onSuccess() { window.location.reload(); } }"
                class="grid gap-4 md:grid-cols-4"
            >
                <?php echo csrf_field(); ?>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Name</label>
                    <input type="text" name="name" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Creditor</label>
                    <input type="text" name="creditor" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</label>
                    <input type="number" name="amount" step="0.01" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Outstanding</label>
                    <input type="number" name="outstanding_balance" step="0.01" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Issued On</label>
                    <input type="date" name="issued_on" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Due Date</label>
                    <input type="date" name="due_date" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                    <select name="status" class="mt-1 w-full rounded-xl border-slate-200">
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                        <option value="settled">Settled</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Team</label>
                    <select name="team_id" class="mt-1 w-full rounded-xl border-slate-200">
                        <option value="">Unassigned</option>
                        <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($team->id); ?>"><?php echo e($team->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Notes</label>
                    <input type="text" name="notes" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                        Save Liability
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Liabilities Register']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Liabilities Register']); ?>
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Liability</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Amounts</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Dates</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <?php $__currentLoopData = $liabilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $liability): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-slate-900"><?php echo e($liability->name); ?></p>
                                    <p class="text-xs text-slate-500"><?php echo e($liability->creditor ?? '—'); ?></p>
                                </td>
                                <td class="px-4 py-4 text-slate-700">
                                    Total: <?php echo e(number_format($liability->amount, 2)); ?> <br>
                                    Outstanding: <?php echo e(number_format($liability->outstanding_balance, 2)); ?>

                                </td>
                                <td class="px-4 py-4 text-slate-700">
                                    Issued: <?php echo e(optional($liability->issued_on)->format('d M Y') ?? '—'); ?> <br>
                                    Due: <?php echo e(optional($liability->due_date)->format('d M Y') ?? '—'); ?>

                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                            'bg-amber-100 text-amber-600' => $liability->status === 'pending',
                                            'bg-emerald-100 text-emerald-600' => $liability->status === 'active',
                                            'bg-slate-200 text-slate-600' => $liability->status === 'settled',
                                            'bg-rose-100 text-rose-600' => $liability->status === 'overdue',
                                        ]); ?>"
                                    ">
                                        <?php echo e(ucfirst($liability->status)); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                    <?php echo e($liabilities->links()); ?>

                </div>
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


<?php echo $__env->make('layouts.admin', ['title' => 'Liabilities'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/liabilities/index.blade.php ENDPATH**/ ?>