

<?php $__env->startSection('header'); ?>
    Reports Dashboard
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Applications</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900"><?php echo e(number_format($stats['total_applications'])); ?></p>
                <p class="text-xs text-slate-400">Pending <?php echo e(number_format($stats['pending_applications'])); ?></p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-500">Approved Applications</p>
                <p class="mt-2 text-3xl font-semibold text-emerald-600"><?php echo e(number_format($stats['approved_applications'])); ?></p>
                <p class="text-xs text-slate-400">Rejected <?php echo e(number_format($stats['rejected_applications'])); ?></p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Disbursed</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">KES <?php echo e(number_format($stats['total_disbursed'], 2)); ?></p>
                <p class="text-xs text-slate-400"><?php echo e(number_format($stats['disbursed_loans'])); ?> applications disbursed</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Active Clients</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900"><?php echo e(number_format($stats['active_clients'])); ?></p>
                <p class="text-xs text-slate-400">Pending approvals <?php echo e(number_format($stats['pending_approvals'])); ?></p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base font-semibold text-slate-900">Monthly Disbursements</p>
                        <p class="text-sm text-slate-500">Past 6 months · success status only</p>
                    </div>
                </div>
                <div class="mt-6">
                    <canvas id="monthlyDisbursementsChart"></canvas>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-base font-semibold text-slate-900">Applications by Stage</p>
                <div class="mt-4 space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $applicationsByStage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-800"><?php echo e(\Illuminate\Support\Str::headline($stage->approval_stage)); ?></p>
                                <p class="text-xs text-slate-400">Awaiting action</p>
                            </div>
                            <span class="text-base font-semibold text-slate-900"><?php echo e($stage->count); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-sm text-slate-500">No pending applications.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-base font-semibold text-slate-900">Recent Applications</p>
                    <p class="text-sm text-slate-500">Latest 10 submissions</p>
                </div>
            </div>
            <div class="mt-4 overflow-hidden rounded-xl border border-slate-100">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Application</th>
                            <th class="px-4 py-3 text-left">Client</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Amount</th>
                            <th class="px-4 py-3 text-left">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $recentApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-4 py-3 font-semibold text-slate-900"><?php echo e($application->application_number); ?></td>
                                <td class="px-4 py-3 text-slate-600"><?php echo e($application->client->full_name ?? '—'); ?></td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                                        <?php echo e($application->status === 'approved' ? 'bg-emerald-50 text-emerald-600' : ($application->status === 'rejected' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600')); ?>">
                                        <?php echo e(\Illuminate\Support\Str::headline($application->status)); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-900">KES <?php echo e(number_format($application->amount, 2)); ?></td>
                                <td class="px-4 py-3 text-slate-500 text-xs"><?php echo e($application->created_at->diffForHumans()); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">
                                    No applications recorded yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('monthlyDisbursementsChart');
            if (!ctx || !window.ChartJS) {
                return;
            }

            const data = <?php echo json_encode($monthlyDisbursements, 15, 512) ?>;
            const labels = data.map(item => item.month);
            const totals = data.map(item => Number(item.total) || 0);

            new window.ChartJS(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'KES Disbursed',
                            data: totals,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.15)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback(value) {
                                    return 'KES ' + value.toLocaleString();
                                },
                            },
                        },
                    },
                },
            });
        });
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', ['title' => 'Reports Dashboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/reports/dashboard.blade.php ENDPATH**/ ?>