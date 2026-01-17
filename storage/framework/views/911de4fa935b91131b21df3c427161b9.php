<?php $__env->startSection('header'); ?>
    <?php echo e($loanApplication->application_number); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="grid gap-6 xl:grid-cols-3">
            <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['class' => 'xl:col-span-2','title' => 'Applicant Overview','description' => 'Key loan details and officers']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'xl:col-span-2','title' => 'Applicant Overview','description' => 'Key loan details and officers']); ?>
                <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Client</dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            <?php echo e($loanApplication->client->full_name); ?>

                            <span class="ml-2 text-xs text-slate-500"><?php echo e($loanApplication->client->phone); ?></span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Team</dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            <?php echo e($loanApplication->team->name ?? 'Unassigned'); ?>

                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Loan Product</dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            <?php echo e($loanApplication->loanProduct->name ?? $loanApplication->loan_type); ?>

                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Business Type</dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            <?php echo e($loanApplication->business_type); ?> · <?php echo e($loanApplication->business_location); ?>

                        </dd>
                    </div>
                </dl>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Financial Summary</p>
                        <div class="mt-4 space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Amount Requested</span>
                                <span class="font-semibold text-slate-900">KES <?php echo e(number_format($loanApplication->amount, 2)); ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Amount Approved</span>
                                <span class="font-semibold text-emerald-600">
                                    <?php echo e($loanApplication->amount_approved ? 'KES ' . number_format($loanApplication->amount_approved, 2) : 'Pending'); ?>

                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Interest</span>
                                <span class="font-semibold text-slate-900">
                                    <?php echo e(number_format($loanApplication->interest_rate, 2)); ?>% · KES <?php echo e(number_format($loanApplication->interest_amount ?? 0, 2)); ?>

                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Total Repayable</span>
                                <span class="font-semibold text-slate-900">
                                    KES <?php echo e(number_format($loanApplication->total_repayment_amount ?? 0, 2)); ?>

                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Officers</p>
                        <dl class="mt-4 space-y-2 text-sm text-slate-700">
                            <div class="flex items-center justify-between">
                                <dt>Loan Officer</dt>
                                <dd class="font-semibold"><?php echo e($loanApplication->loanOfficer->name ?? 'Not assigned'); ?></dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt>Credit Officer</dt>
                                <dd class="font-semibold"><?php echo e($loanApplication->creditOfficer->name ?? 'Not assigned'); ?></dd>
                            </div>
                            <div>
                                <dt>Collection Officer</dt>
                                <dd class="font-semibold"><?php echo e($loanApplication->collectionOfficer->name ?? 'Not assigned'); ?></dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt>Finance Officer</dt>
                                <dd class="font-semibold"><?php echo e($loanApplication->financeOfficer->name ?? 'Not assigned'); ?></dd>
                            </div>
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

            <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Status','description' => 'Current stage & workflow']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Status','description' => 'Current stage & workflow']); ?>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2">
                        <span class="text-slate-500">Stage</span>
                        <span class="font-semibold text-slate-900"><?php echo e(ucfirst(str_replace('_', ' ', $loanApplication->approval_stage))); ?></span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2">
                        <span class="text-slate-500">Status</span>
                        <span class="font-semibold <?php echo e($loanApplication->status === 'approved' ? 'text-emerald-600' : ($loanApplication->status === 'rejected' ? 'text-rose-500' : 'text-slate-900')); ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $loanApplication->status))); ?>

                        </span>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white px-3 py-3">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Timeline</p>
                        <ol class="mt-3 space-y-3 text-xs text-slate-600">
                            <?php $__currentLoopData = $loanApplication->approvals()->latest()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $approval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <span class="font-semibold text-slate-800"><?php echo e(ucfirst(str_replace('_', ' ', $approval->approval_level))); ?></span>
                                    by <?php echo e($approval->approver->name ?? 'System'); ?> —
                                    <span class="text-slate-400"><?php echo e($approval->approved_at->diffForHumans()); ?></span>
                                    <?php if($approval->comment): ?>
                                        <p class="mt-1 italic text-slate-500">"<?php echo e($approval->comment); ?>"</p>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>
                    </div>

                    <?php
                        $disbursement = $loanApplication->loan?->disbursements->first() ?? $loanApplication->disbursements->first() ?? null;
                        $hasRole = auth()->user()->hasAnyRole(['Admin', 'Finance Officer', 'Director']);
                        $hasSuccessfulDisbursement = $disbursement && $disbursement->status === 'success';
                    ?>

                    <?php if($loanApplication->isApproved() && $hasRole && !$hasSuccessfulDisbursement): ?>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <?php if($disbursement && in_array($disbursement->status, ['pending', 'processing'])): ?>
                                <a
                                    href="<?php echo e(route('disbursements.show', $disbursement)); ?>"
                                    class="inline-flex items-center rounded-lg bg-emerald-500 px-3 py-1 text-xs font-semibold text-white hover:bg-emerald-600"
                                >
                                    View Disbursement
                                </a>
                            <?php elseif(!$disbursement): ?>
                                <a
                                    href="<?php echo e(route('disbursements.create', $loanApplication)); ?>"
                                    class="inline-flex items-center rounded-lg bg-emerald-500 px-3 py-1 text-xs font-semibold text-white hover:bg-emerald-600"
                                >
                                    Disburse Now
                                </a>
                            <?php endif; ?>
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

        <div class="grid gap-6 lg:grid-cols-2">
            <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Supporting Documents','description' => 'KYC & business documents']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Supporting Documents','description' => 'KYC & business documents']); ?>
                <div class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                    <?php if($loanApplication->loan_form_path): ?>
                        <a href="<?php echo e(Storage::disk('public')->url($loanApplication->loan_form_path)); ?>" target="_blank" class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 hover:border-emerald-300">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 12l9-4.5-9-4.5-9 4.5 9 4.5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 12l9-4.5-9-4.5-9 4.5 9 4.5zm0 0v9" />
                                </svg>
                            </span>
                            <span>
                                <span class="block font-semibold text-slate-900">Loan Form</span>
                                <span class="text-xs text-slate-500">View Uploaded</span>
                            </span>
                        </a>
                    <?php endif; ?>

                    <?php if($loanApplication->mpesa_statement_path): ?>
                        <a href="<?php echo e(Storage::disk('public')->url($loanApplication->mpesa_statement_path)); ?>" target="_blank" class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 hover:border-emerald-300">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-sky-100 text-sky-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <span>
                                <span class="block font-semibold text-slate-900">M-PESA Statement</span>
                                <span class="text-xs text-slate-500">View Uploaded</span>
                            </span>
                        </a>
                    <?php endif; ?>

                    <?php if($loanApplication->business_photo_path): ?>
                        <div class="rounded-xl border border-slate-200 p-3">
                            <p class="text-xs font-semibold text-slate-500">Business Photo</p>
                            <img src="<?php echo e(Storage::disk('public')->url($loanApplication->business_photo_path)); ?>" class="mt-2 h-32 w-full rounded-lg object-cover">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-6">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">KYC Documents</p>
                    <div class="mt-3 grid gap-3 md:grid-cols-2">
                        <?php $__empty_1 = true; $__currentLoopData = $loanApplication->kycDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="<?php echo e(Storage::disk('public')->url($document->file_path)); ?>" target="_blank" class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 text-sm hover:border-emerald-300">
                                <div>
                                    <p class="font-semibold text-slate-900"><?php echo e(ucfirst($document->document_type)); ?></p>
                                    <p class="text-xs text-slate-500"><?php echo e($document->document_name); ?></p>
                                </div>
                                <span class="text-xs text-emerald-600">View</span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-sm text-slate-500">No KYC documents uploaded.</p>
                        <?php endif; ?>
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

            <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Activity Log','description' => 'Important actions & approvals']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Activity Log','description' => 'Important actions & approvals']); ?>
                <div class="space-y-4 text-xs text-slate-600">
                    <?php $__empty_1 = true; $__currentLoopData = $auditLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="rounded-xl border border-slate-200 px-4 py-3">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-slate-800"><?php echo e(ucfirst(str_replace('_', ' ', $log->action))); ?></span>
                                <span class="text-slate-400"><?php echo e($log->created_at->diffForHumans()); ?></span>
                            </div>
                            <p class="mt-1 text-slate-500"><?php echo e($log->description); ?></p>
                            <?php if($log->user): ?>
                                <p class="mt-1 text-slate-400">By <?php echo e($log->user->name); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-sm text-slate-500">No activity logged yet.</p>
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
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', ['title' => $loanApplication->application_number], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wilchar\resources\views/admin/loan-applications/show.blade.php ENDPATH**/ ?>