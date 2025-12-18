<?php $__env->startSection('header'); ?>
    Loan Applications
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div
        x-data="{
            filters: {
                status: '<?php echo e(request('status')); ?>',
                stage: '<?php echo e(request('stage')); ?>',
                team_id: '<?php echo e(request('team_id')); ?>',
            },
            init() {
                window.addEventListener('loan-applications:refresh', () => {
                    window.location.reload();
                });
            },
            openEditModal(id) {
                const urlTemplate = '<?php echo e(route('loan-applications.edit', ['loan_application' => '__ID__'])); ?>';
                const url = urlTemplate.replace('__ID__', id);
                Admin.showModal({ title: 'Edit Loan Application', url, method: 'get', size: 'xl' });
            }
        }"
        class="space-y-6"
        x-ref="loanApplicationsPage"
    >
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3 text-sm text-slate-600">
                <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2">
                    <span class="text-xs uppercase tracking-wide text-slate-500">Total</span>
                    <span class="font-semibold text-slate-900"><?php echo e(number_format($applications->total())); ?></span>
                </div>
                <div class="hidden md:block h-6 w-px bg-slate-200"></div>
                <div class="flex flex-wrap gap-2">
                    <select class="rounded-xl border-slate-200 text-sm" x-model="filters.stage" @change="window.location = '<?php echo e(route('loan-applications.index')); ?>?stage=' + filters.stage">
                        <option value="">Stage: All</option>
                        <option value="loan_officer" <?php if(request('stage') === 'loan_officer'): echo 'selected'; endif; ?>>Loan Officer</option>
                        <option value="credit_officer" <?php if(request('stage') === 'credit_officer'): echo 'selected'; endif; ?>>Credit Officer</option>
                        <option value="finance_officer" <?php if(request('stage') === 'finance_officer'): echo 'selected'; endif; ?>>Finance Officer</option>
                        <option value="director" <?php if(request('stage') === 'director'): echo 'selected'; endif; ?>>Director</option>
                        <option value="completed" <?php if(request('stage') === 'completed'): echo 'selected'; endif; ?>>Completed</option>
                    </select>
                    <select class="rounded-xl border-slate-200 text-sm" x-model="filters.status" @change="window.location = '<?php echo e(route('loan-applications.index')); ?>?status=' + filters.status">
                        <option value="">Status: All</option>
                        <option value="submitted" <?php if(request('status') === 'submitted'): echo 'selected'; endif; ?>>Submitted</option>
                        <option value="under_review" <?php if(request('status') === 'under_review'): echo 'selected'; endif; ?>>Under Review</option>
                        <option value="approved" <?php if(request('status') === 'approved'): echo 'selected'; endif; ?>>Approved</option>
                        <option value="rejected" <?php if(request('status') === 'rejected'): echo 'selected'; endif; ?>>Rejected</option>
                    </select>
                </div>
            </div>
            <a
                href="<?php echo e(route('loan-applications.create')); ?>"
                class="flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-400"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                New Application
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Application</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Team & Officers</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Stage</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-4">
                                <div class="font-semibold text-slate-900"><?php echo e($application->application_number); ?></div>
                                <div class="text-xs text-slate-500">
                                    <?php echo e($application->client->full_name); ?> · <?php echo e($application->client->phone); ?>

                                </div>
                                <div class="mt-1 text-xs text-slate-400">
                                    Submitted <?php echo e($application->created_at->diffForHumans()); ?>

                                </div>
                            </td>
                            <td class="px-4 py-4 text-slate-600">
                                <p class="font-medium"><?php echo e($application->team->name ?? '—'); ?></p>
                                <p class="text-xs text-slate-500">
                                    Loan Officer: <?php echo e($application->loanOfficer->name ?? 'Pending'); ?>

                                </p>
                                <p class="text-xs text-slate-500">
                                    Credit Officer: <?php echo e($application->creditOfficer->name ?? 'Pending'); ?>

                                </p>
                                <p class="text-xs text-slate-500">
                                    Collections: <?php echo e($application->collectionOfficer->name ?? 'Pending'); ?>

                                </p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-semibold text-slate-900">KES <?php echo e(number_format($application->amount, 2)); ?></p>
                                <p class="text-xs text-slate-500">Interest <?php echo e(number_format($application->interest_rate, 2)); ?>%</p>
                                <p class="text-xs text-slate-500">Total <?php echo e(number_format($application->total_repayment_amount ?? 0, 2)); ?></p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $application->approval_stage))); ?>

                                </span>
                                <span
                                    class="ml-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                        <?php echo e($application->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($application->status === 'rejected' ? 'bg-rose-100 text-rose-600' : 'bg-amber-100 text-amber-700')); ?>"
                                >
                                    <?php echo e(ucfirst(str_replace('_', ' ', $application->status))); ?>

                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="<?php echo e(route('loan-applications.show', $application)); ?>" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                        View
                                    </a>
                                    <?php if($application->approval_stage === 'loan_officer' && $application->status === 'submitted'): ?>
                                        <button @click="openEditModal(<?php echo e($application->id); ?>)" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                            Edit
                                        </button>
                                    <?php endif; ?>
                                    <a href="<?php echo e(route('approvals.show', $application)); ?>" class="rounded-lg bg-emerald-500 px-3 py-1 text-xs font-semibold text-white hover:bg-emerald-600">
                                        Approve
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">
                                No applications found. Start by creating a new loan application.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                <?php echo e($applications->withQueryString()->links()); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', ['title' => 'Loan Applications'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wilchar\resources\views/admin/loan-applications/index.blade.php ENDPATH**/ ?>