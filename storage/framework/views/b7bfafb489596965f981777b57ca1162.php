

<?php $__env->startSection('header'); ?>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold text-slate-900"><?php echo e($loan->loan_type); ?></h1>
            <p class="text-sm text-slate-500"><?php echo e($loan->client->full_name); ?> · <?php echo e($loan->client->phone); ?></p>
        </div>
        <?php if($loan->status === 'disbursed' && $loan->outstanding_balance > 0): ?>
            <button
                type="button"
                @click="$store.modal?.open('payment-modal')"
                class="rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-400"
            >
                <svg class="mr-2 inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Record Payment
            </button>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <!-- Loan Summary Cards -->
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Total Amount</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">KES <?php echo e(number_format($loan->total_amount, 2)); ?></p>
                <p class="mt-1 text-xs text-slate-500">Principal: <?php echo e(number_format($loan->amount_approved, 2)); ?></p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Outstanding Balance</p>
                <p class="mt-2 text-2xl font-semibold text-rose-500">KES <?php echo e(number_format($loan->outstanding_balance, 2)); ?></p>
                <p class="mt-1 text-xs text-slate-500">Total Paid: <?php echo e(number_format($totalPaid, 2)); ?></p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Interest Amount</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">KES <?php echo e(number_format($loan->interest_amount, 2)); ?></p>
                <p class="mt-1 text-xs text-slate-500">Rate: <?php echo e(number_format($loan->interest_rate, 2)); ?>%</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Progress</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900"><?php echo e($totalInstalments > 0 ? round(($paidInstalments / $totalInstalments) * 100, 1) : 0); ?>%</p>
                <p class="mt-1 text-xs text-slate-500"><?php echo e($paidInstalments); ?> of <?php echo e($totalInstalments); ?> instalments</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <!-- Loan Details -->
            <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['class' => 'xl:col-span-2','title' => 'Loan Information']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'xl:col-span-2','title' => 'Loan Information']); ?>
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-slate-900">Basic Details</h3>
                        <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Loan Type</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loan->loan_type); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                        <?php echo e($loan->status === 'disbursed' ? 'bg-emerald-100 text-emerald-800' : ''); ?>

                                        <?php echo e($loan->status === 'closed' ? 'bg-slate-100 text-slate-800' : ''); ?>

                                        <?php echo e($loan->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                        <?php echo e($loan->status === 'approved' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                    ">
                                        <?php echo e(ucfirst($loan->status)); ?>

                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Loan Product</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loan->loanProduct->name ?? 'Custom Product'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Team</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loan->team->name ?? 'Unassigned'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Term</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loan->term_months); ?> months</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Repayment Frequency</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e(ucfirst($loan->repayment_frequency ?? 'Monthly')); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Amount Requested</dt>
                                <dd class="mt-1 font-semibold text-slate-900">KES <?php echo e(number_format($loan->amount_requested, 2)); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Amount Approved</dt>
                                <dd class="mt-1 font-semibold text-slate-900">KES <?php echo e(number_format($loan->amount_approved, 2)); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Processing Fee</dt>
                                <dd class="mt-1 font-semibold text-slate-900">KES <?php echo e(number_format($loan->processing_fee ?? 0, 2)); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Late Fees</dt>
                                <dd class="mt-1 font-semibold text-slate-900">KES <?php echo e(number_format($loan->late_fee_accrued ?? 0, 2)); ?></dd>
                            </div>
                            <?php if($loan->next_due_date): ?>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Next Due Date</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loan->next_due_date->format('d M Y')); ?></dd>
                            </div>
                            <?php endif; ?>
                            <?php if($nextInstalment): ?>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Next Instalment</dt>
                                <dd class="mt-1 font-semibold text-slate-900">
                                    KES <?php echo e(number_format($nextInstalment->total_amount - $nextInstalment->amount_paid, 2)); ?>

                                    <span class="text-xs text-slate-500">(Due: <?php echo e($nextInstalment->due_date->format('d M Y')); ?>)</span>
                                </dd>
                            </div>
                            <?php endif; ?>
                        </dl>
                    </div>

                    <!-- Client Information -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-slate-900">Client Information</h3>
                        <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Full Name</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loan->client->full_name); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Phone</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loan->client->phone); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Email</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loan->client->email ?? '—'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">ID Number</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loan->client->id_number ?? '—'); ?></dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Officer Assignments -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-slate-900">Officer Assignments</h3>
                        <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Collection Officer</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loan->collectionOfficer->name ?? '—'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Recovery Officer</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loan->recoveryOfficer->name ?? '—'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Finance Officer</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loan->financeOfficer->name ?? '—'); ?></dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Disbursements -->
                    <?php if($loan->disbursements->count() > 0): ?>
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-slate-900">Disbursements</h3>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $loan->disbursements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $disbursement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-slate-900">KES <?php echo e(number_format($disbursement->amount, 2)); ?></p>
                                            <p class="text-xs text-slate-500">
                                                <?php echo e(ucfirst($disbursement->method)); ?> · <?php echo e($disbursement->disbursement_date->format('d M Y')); ?>

                                            </p>
                                        </div>
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                            <?php echo e($disbursement->status === 'success' ? 'bg-emerald-100 text-emerald-800' : ''); ?>

                                            <?php echo e($disbursement->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                            <?php echo e($disbursement->status === 'failed' ? 'bg-rose-100 text-rose-800' : ''); ?>

                                        ">
                                            <?php echo e(ucfirst($disbursement->status)); ?>

                                        </span>
                                    </div>
                                    <?php if($disbursement->reference): ?>
                                        <p class="mt-1 text-xs text-slate-500">Reference: <?php echo e($disbursement->reference); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
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

            <!-- Quick Actions & Stats -->
            <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Quick Actions']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Quick Actions']); ?>
                <div class="space-y-4">
                    <form action="<?php echo e(route('loans.update', $loan)); ?>" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                            <select name="status" class="mt-1 w-full rounded-xl border-slate-200">
                                <?php $__currentLoopData = ['pending', 'approved', 'disbursed', 'closed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($status); ?>" <?php if($loan->status === $status): echo 'selected'; endif; ?>><?php echo e(ucfirst($status)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Next Due Date</label>
                            <input type="date" name="next_due_date" class="mt-1 w-full rounded-xl border-slate-200" value="<?php echo e(optional($loan->next_due_date)->format('Y-m-d')); ?>">
                        </div>
                        <button type="submit" class="w-full rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                            Update Loan
                        </button>
                    </form>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <h4 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Loan Statistics</h4>
                        <dl class="mt-3 space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-600">Total Disbursed</dt>
                                <dd class="font-semibold text-slate-900">KES <?php echo e(number_format($totalDisbursed, 2)); ?></dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-600">Total Paid</dt>
                                <dd class="font-semibold text-emerald-600">KES <?php echo e(number_format($totalPaid, 2)); ?></dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-600">Repayments Count</dt>
                                <dd class="font-semibold text-slate-900"><?php echo e($loan->repayments->count()); ?></dd>
                            </div>
                            <?php if($overdueInstalments > 0): ?>
                            <div class="flex items-center justify-between">
                                <dt class="text-rose-600">Overdue Instalments</dt>
                                <dd class="font-semibold text-rose-600"><?php echo e($overdueInstalments); ?></dd>
                            </div>
                            <?php endif; ?>
                        </dl>
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

        <!-- Repayments & Instalments -->
        <div class="grid gap-6 lg:grid-cols-2">
            <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Repayment History']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Repayment History']); ?>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $loan->repayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repayment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">KES <?php echo e(number_format($repayment->amount, 2)); ?></p>
                                    <p class="text-xs text-slate-500">
                                        <?php echo e(ucfirst($repayment->payment_method)); ?>

                                        <?php if($repayment->reference): ?>
                                            · <?php echo e($repayment->reference); ?>

                                        <?php endif; ?>
                                    </p>
                                    <?php if($repayment->receiver): ?>
                                        <p class="text-xs text-slate-400">Received by <?php echo e($repayment->receiver->name); ?></p>
                                    <?php endif; ?>
                                </div>
                                <span class="text-xs text-slate-500"><?php echo e($repayment->paid_at?->format('d M Y')); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-sm text-slate-500">No repayments recorded yet.</p>
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

            <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Instalment Schedule']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Instalment Schedule']); ?>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $loan->instalments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instalment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3
                            <?php echo e($instalment->status === 'overdue' ? 'border-rose-200 bg-rose-50' : ''); ?>

                            <?php echo e($instalment->status === 'paid' ? 'border-emerald-200 bg-emerald-50' : ''); ?>

                        ">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900"><?php echo e($instalment->due_date->format('d M Y')); ?></p>
                                    <p class="text-xs text-slate-500">
                                        Amount: KES <?php echo e(number_format($instalment->total_amount, 2)); ?>

                                        <?php if($instalment->amount_paid > 0): ?>
                                            · Paid: KES <?php echo e(number_format($instalment->amount_paid, 2)); ?>

                                        <?php endif; ?>
                                    </p>
                                </div>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    <?php echo e($instalment->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ''); ?>

                                    <?php echo e($instalment->status === 'overdue' ? 'bg-rose-100 text-rose-800' : ''); ?>

                                    <?php echo e($instalment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                ">
                                    <?php echo e(ucfirst($instalment->status)); ?>

                                </span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-sm text-slate-500">No instalment schedule available.</p>
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

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('loans.delete')): ?>
            <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Danger zone','description' => 'Removes this loan record and all data tied to it: repayments, instalments, collection entries, performance logs, and ledger transactions for this loan. The linked loan application and disbursement history are kept; only the loan_id link on the application is cleared. You must verify by email code.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Danger zone','description' => 'Removes this loan record and all data tied to it: repayments, instalments, collection entries, performance logs, and ledger transactions for this loan. The linked loan application and disbursement history are kept; only the loan_id link on the application is cleared. You must verify by email code.']); ?>
                <div class="space-y-4 rounded-2xl border border-rose-200 bg-rose-50/50 p-5">
                    <p class="text-sm text-rose-900">
                        This cannot be undone. Click below to receive a 6-digit code at <span class="font-semibold"><?php echo e(auth()->user()?->email); ?></span>, then enter it to confirm.
                    </p>
                    <form method="POST" action="<?php echo e(route('loans.send-delete-otp', $loan)); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button
                            type="submit"
                            class="rounded-xl border border-rose-300 bg-white px-4 py-2 text-sm font-semibold text-rose-700 shadow-sm hover:bg-rose-100"
                        >
                            Email verification code
                        </button>
                    </form>

                    <form
                        id="delete-loan-form-<?php echo e($loan->id); ?>"
                        method="POST"
                        action="<?php echo e(route('loans.destroy', $loan)); ?>"
                        class="space-y-3 border-t border-rose-200 pt-4"
                    >
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-rose-800">6-digit code</label>
                            <input
                                type="text"
                                name="otp"
                                inputmode="numeric"
                                pattern="[0-9]{6}"
                                maxlength="6"
                                autocomplete="one-time-code"
                                class="mt-1 w-full max-w-xs rounded-xl border-rose-200 bg-white <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-500 ring-1 ring-rose-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="000000"
                            >
                            <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-xs text-rose-700"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <button
                            type="button"
                            class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-700"
                            onclick="(async () => { const ok = await Admin.confirmAction({ title: 'Delete this loan permanently?', text: 'All repayments and schedules for this loan will be removed. This cannot be undone.', icon: 'error', confirmButtonText: 'Delete loan' }); if (ok) document.getElementById('delete-loan-form-<?php echo e($loan->id); ?>').submit(); })()"
                        >
                            Delete loan permanently
                        </button>
                    </form>
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
        <?php endif; ?>
    </div>

    <!-- Payment Modal -->
    <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['name' => 'payment-modal','title' => 'Record Payment']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'payment-modal','title' => 'Record Payment']); ?>
        <form
            action="<?php echo e(route('collections.store')); ?>"
            method="POST"
            x-ajax="{
                successMessage: { title: 'Success', text: 'Payment recorded successfully.' },
                onSuccess(response) {
                    window.location.reload();
                }
            }"
            class="space-y-4"
        >
            <?php echo csrf_field(); ?>
            <input type="hidden" name="loan_id" value="<?php echo e($loan->id); ?>">

            <div>
                <label class="text-sm font-medium text-slate-700">Payment Amount (KES)</label>
                <input
                    type="number"
                    name="amount"
                    step="0.01"
                    min="0.01"
                    max="<?php echo e($loan->outstanding_balance); ?>"
                    value="<?php echo e($nextInstalment ? ($nextInstalment->total_amount - $nextInstalment->amount_paid) : $loan->outstanding_balance); ?>"
                    class="mt-1 w-full rounded-xl border-slate-200"
                    required
                >
                <p class="mt-1 text-xs text-slate-500">Outstanding: KES <?php echo e(number_format($loan->outstanding_balance, 2)); ?></p>
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">Payment Method</label>
                <select name="payment_method" class="mt-1 w-full rounded-xl border-slate-200" required>
                    <option value="">Select method</option>
                    <option value="mpesa">M-Pesa</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="cash">Cash</option>
                    <option value="cheque">Cheque</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">Payment Date</label>
                <input
                    type="date"
                    name="paid_at"
                    value="<?php echo e(now()->format('Y-m-d')); ?>"
                    class="mt-1 w-full rounded-xl border-slate-200"
                    required
                >
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">Reference/Receipt Number</label>
                <input
                    type="text"
                    name="reference"
                    placeholder="e.g., M-Pesa receipt number"
                    class="mt-1 w-full rounded-xl border-slate-200"
                >
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">Receipt URL (Optional)</label>
                <input
                    type="url"
                    name="receipt_url"
                    placeholder="https://..."
                    class="mt-1 w-full rounded-xl border-slate-200"
                >
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <button
                    type="button"
                    @click="$store.modal?.close()"
                    class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-400"
                >
                    Record Payment
                </button>
            </div>
        </form>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', ['title' => 'Loan Detail'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views\admin\loans\show.blade.php ENDPATH**/ ?>