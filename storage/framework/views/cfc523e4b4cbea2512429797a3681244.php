<?php $__env->startSection('header'); ?>
    Review <?php echo e($loanApplication->application_number); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid gap-6 xl:grid-cols-3">
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['class' => 'xl:col-span-2','title' => 'Loan Snapshot','description' => 'Double-check details before approving']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'xl:col-span-2','title' => 'Loan Snapshot','description' => 'Double-check details before approving']); ?>
            <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Client</dt>
                    <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loanApplication->client->full_name); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Application #</dt>
                    <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loanApplication->application_number); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Stage</dt>
                    <dd class="mt-1 font-semibold text-emerald-600"><?php echo e(ucfirst(str_replace('_', ' ', $loanApplication->approval_stage))); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Amount Requested</dt>
                    <dd class="mt-1 font-semibold text-slate-900">KES <?php echo e(number_format($loanApplication->amount, 2)); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Interest</dt>
                    <dd class="mt-1 font-semibold text-slate-900"><?php echo e(number_format($loanApplication->interest_rate ?? 0, 2)); ?>%</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Loan Product</dt>
                    <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loanApplication->loanProduct->name ?? '—'); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Business</dt>
                    <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loanApplication->business_type); ?> · <?php echo e($loanApplication->business_location); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Purpose</dt>
                    <dd class="mt-1 text-slate-700"><?php echo e($loanApplication->purpose ?? '—'); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Team</dt>
                    <dd class="mt-1 font-semibold text-slate-900"><?php echo e($loanApplication->team->name ?? 'Unassigned'); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Loan Officer</dt>
                    <dd class="mt-1 text-slate-700"><?php echo e($loanApplication->loanOfficer->name ?? '—'); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Credit Officer</dt>
                    <dd class="mt-1 text-slate-700"><?php echo e($loanApplication->creditOfficer->name ?? '—'); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Finance Officer</dt>
                    <dd class="mt-1 text-slate-700"><?php echo e($loanApplication->financeOfficer->name ?? '—'); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Repayment Period</dt>
                    <dd class="mt-1 font-semibold text-slate-900">
                        <?php if($loanApplication->loan): ?>
                            <?php echo e($loanApplication->loan->term_months); ?> months
                        <?php else: ?>
                            <?php echo e($loanApplication->duration_months ?? '—'); ?> months
                        <?php endif; ?>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Number of Instalments</dt>
                    <dd class="mt-1 font-semibold text-slate-900">
                        <?php if($loanApplication->loan && $loanApplication->loan->instalments): ?>
                            <?php echo e($loanApplication->loan->instalments->count()); ?>

                        <?php else: ?>
                            <?php echo e($loanApplication->duration_months ?? '—'); ?>

                        <?php endif; ?>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Repayment Frequency</dt>
                    <dd class="mt-1 font-semibold text-slate-900"><?php echo e(ucfirst(str_replace('_', ' ', $loanApplication->repayment_frequency ?? 'monthly'))); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Weekly/Cycle Payment</dt>
                    <dd class="mt-1 font-semibold text-slate-900">
                        <?php if($loanApplication->weekly_payment_amount): ?>
                            Weekly · KES <?php echo e(number_format($loanApplication->weekly_payment_amount, 2)); ?>

                        <?php elseif($loanApplication->repayment_cycle_amount): ?>
                            Cycle · KES <?php echo e(number_format($loanApplication->repayment_cycle_amount, 2)); ?>

                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Total Repayment</dt>
                    <dd class="mt-1 font-semibold text-slate-900">KES <?php echo e(number_format($loanApplication->total_repayment_amount ?? ($loanApplication->amount + $loanApplication->interest_amount + ($loanApplication->registration_fee ?? 0)), 2)); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Registration / Processing Fee</dt>
                    <dd class="mt-1 font-semibold text-slate-900">KES <?php echo e(number_format($loanApplication->registration_fee ?? 0, 2)); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Submitted</dt>
                    <dd class="mt-1 text-slate-700"><?php echo e($loanApplication->created_at?->format('d M Y, H:i')); ?></dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Last Updated</dt>
                    <dd class="mt-1 text-slate-700"><?php echo e($loanApplication->updated_at?->diffForHumans()); ?></dd>
                </div>
            </dl>
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
        <?php
            $requiredPermissionLabels = [
                'loan_officer' => 'approvals.approve-loan-officer',
                'credit_officer' => 'approvals.approve-credit-officer',
                'finance_officer' => 'approvals.approve-finance-officer',
                'director' => 'approvals.approve-director',
            ];
            $requiredPermission = $requiredPermissionLabels[$loanApplication->approval_stage] ?? 'approvals.approve';
            
            $requiredRoleLabels = [
                'loan_officer' => 'Loan Officer, Marketer',
                'credit_officer' => 'Credit Officer',
                'finance_officer' => 'Finance Officer, Director',
                'director' => 'Director',
            ];
            $requiredRole = $requiredRoleLabels[$loanApplication->approval_stage] ?? 'authorized user';
        ?>
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Approval Action','description' => 'Provide comments & approve or reject','class' => 'space-y-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Approval Action','description' => 'Provide comments & approve or reject','class' => 'space-y-5']); ?>
            <?php if (! ($canApprove)): ?>
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">
                    <p class="font-semibold">Action locked</p>
                    <p class="mt-1 text-xs text-amber-600">
                        Only users with the <strong><?php echo e(str_replace('approvals.approve-', '', str_replace('_', ' ', $requiredPermission))); ?></strong> permission, <?php echo e($requiredRole); ?> role, or Admin can take action at this stage. You can still view the details, but approval buttons are disabled.
                    </p>
                </div>
            <?php endif; ?>
            <form
                x-data="{ stage: '<?php echo e($loanApplication->approval_stage); ?>', confirmApprove(event) { event.preventDefault(); Admin.confirmAction({ title: 'Approve Application?', text: 'This will move the application to the next stage.', confirmButtonText: 'Approve' }).then(confirmed => { if (confirmed) event.target.submit(); }); } }"
                method="POST"
                action="<?php echo e(route('approvals.approve', $loanApplication)); ?>"
                <?php if($canApprove): ?>
                    @submit="confirmApprove($event)"
                <?php endif; ?>
                class="space-y-4"
            >
                <?php echo csrf_field(); ?>
                <fieldset class="space-y-4" <?php if(!$canApprove): echo 'disabled'; endif; ?>>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Comment</label>
                        <textarea name="comment" rows="3" class="mt-1 w-full rounded-xl border-slate-200 text-sm"><?php echo e(old('comment')); ?></textarea>
                    </div>

                    <?php if($loanApplication->approval_stage === 'credit_officer'): ?>
                        <div class="grid gap-3 text-sm md:grid-cols-2">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Amount Approved</label>
                                <input type="number" name="amount_approved" class="mt-1 w-full rounded-xl border-slate-200" min="1000" value="<?php echo e(old('amount_approved', $loanApplication->amount_approved ?? $loanApplication->amount)); ?>" required>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Interest Rate (%)</label>
                                <input type="number" name="interest_rate" step="0.01" class="mt-1 w-full rounded-xl border-slate-200" value="<?php echo e(old('interest_rate', $loanApplication->interest_rate ?? 12)); ?>" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Duration (Months)</label>
                                <input type="number" name="duration_months" class="mt-1 w-full rounded-xl border-slate-200" value="<?php echo e(old('duration_months', $loanApplication->duration_months ?? 12)); ?>" required>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($loanApplication->approval_stage === 'finance_officer'): ?>
                        <div class="grid gap-3 text-sm md:grid-cols-2">
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Amount to Disburse</label>
                                <input type="number" name="amount_approved" class="mt-1 w-full rounded-xl border-slate-200" value="<?php echo e(old('amount_approved', $loanApplication->amount_approved ?? $loanApplication->amount)); ?>" required>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Processing Fee</label>
                                <input type="number" name="processing_fee" step="0.01" class="mt-1 w-full rounded-xl border-slate-200" value="<?php echo e(old('processing_fee', 0)); ?>">
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Recipient Phone</label>
                                <input type="text" name="recipient_phone" pattern="^254[0-9]{9}$" class="mt-1 w-full rounded-xl border-slate-200" value="<?php echo e(old('recipient_phone', $loanApplication->client->phone ?? '')); ?>" placeholder="254712345678" required>
                                <p class="mt-1 text-xs text-slate-500">Format: 254712345678</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Disbursement Method</label>
                                <input type="text" name="disbursement_method" class="mt-1 w-full rounded-xl border-slate-200" value="<?php echo e(old('disbursement_method', 'M-PESA B2C')); ?>" required>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($loanApplication->approval_stage === 'director'): ?>
                        <?php
                            $pending = $loanApplication->onboarding_data['pending_disbursement'] ?? [];
                            $otpSent = !empty($pending['otp_sent_at']);
                        ?>
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50/60 p-4 text-sm text-slate-700">
                            <p class="font-semibold text-slate-900">Finance officer has prepared disbursement instructions.</p>
                            <p class="mt-1 text-xs text-slate-500">Review and approve to release funds.</p>
                            <?php if($otpSent): ?>
                                <p class="mt-2 text-xs font-medium text-emerald-700">
                                    ✓ OTP sent to your email. Please check your inbox and enter the code below.
                                </p>
                            <?php else: ?>
                                <p class="mt-2 text-xs text-amber-600">
                                    An OTP will be sent to your email when you view this page.
                                </p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email OTP <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="otp" 
                                class="mt-1 w-full rounded-xl border-slate-200 text-sm font-mono text-center text-lg tracking-widest" 
                                placeholder="000000"
                                maxlength="6"
                                pattern="[0-9]{6}"
                                inputmode="numeric"
                                required
                                autocomplete="one-time-code"
                            >
                            <p class="mt-1 text-xs text-slate-500">Enter the 6-digit code sent to your email address</p>
                            <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    <?php endif; ?>
                </fieldset>

                <button
                    type="submit"
                    <?php if(!$canApprove): echo 'disabled'; endif; ?>
                    class="w-full rounded-xl px-4 py-2 text-sm font-semibold text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 <?php echo e($canApprove ? 'bg-emerald-500 hover:bg-emerald-600' : 'bg-slate-300 cursor-not-allowed'); ?>"
                >
                    Approve & Continue
                </button>
            </form>

            <form
                method="POST"
                action="<?php echo e(route('approvals.reject', $loanApplication)); ?>"
                class="space-y-3"
                x-data
                <?php if($canApprove): ?>
                    @submit.prevent="Admin.confirmAction({ title: 'Reject Application?', text: 'This will mark the application as rejected.', icon: 'error', confirmButtonText: 'Reject' }).then(confirmed => { if(confirmed) $el.submit(); })"
                <?php endif; ?>
            >
                <?php echo csrf_field(); ?>
                <fieldset class="space-y-3" <?php if(!$canApprove): echo 'disabled'; endif; ?>>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rejection Reason</label>
                    <textarea name="rejection_reason" rows="3" class="w-full rounded-xl border-slate-200 text-sm" required><?php echo e(old('rejection_reason')); ?></textarea>
                </fieldset>
                <button
                    type="submit"
                    <?php if(!$canApprove): echo 'disabled'; endif; ?>
                    class="w-full rounded-xl border px-4 py-2 text-sm font-semibold <?php echo e($canApprove ? 'border-rose-300 bg-white text-rose-600 hover:bg-rose-50' : 'border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed'); ?>"
                >
                    Reject Application
                </button>
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

    <?php if(isset($latestDisbursement) && $latestDisbursement): ?>
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['class' => 'mt-6','title' => 'Disbursement & B2C Payment Status']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-6','title' => 'Disbursement & B2C Payment Status']); ?>
            <div class="space-y-4">
                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Disbursement Details</p>
                            <p class="mt-1 text-xs text-slate-500">
                                Amount: KES <?php echo e(number_format($latestDisbursement->amount, 2)); ?>

                                <?php if($latestDisbursement->processing_fee > 0): ?>
                                    · Fee: KES <?php echo e(number_format($latestDisbursement->processing_fee, 2)); ?>

                                <?php endif; ?>
                            </p>
                            <p class="mt-1 text-xs text-slate-500">
                                Method: <?php echo e(ucfirst(str_replace('_', ' ', $latestDisbursement->method))); ?>

                                <?php if($latestDisbursement->recipient_phone): ?>
                                    · Phone: <?php echo e($latestDisbursement->recipient_phone); ?>

                                <?php endif; ?>
                            </p>
                        </div>
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                            <?php echo e($latestDisbursement->status === 'success' ? 'bg-emerald-100 text-emerald-800' : ''); ?>

                            <?php echo e($latestDisbursement->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                            <?php echo e($latestDisbursement->status === 'failed' ? 'bg-rose-100 text-rose-800' : ''); ?>

                        ">
                            <?php echo e(ucfirst($latestDisbursement->status)); ?>

                        </span>
                    </div>
                </div>

                <?php if($latestDisbursement->method === 'mpesa_b2c'): ?>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm font-semibold text-slate-900">M-Pesa B2C Payment Status</p>
                        <?php if($latestDisbursement->status === 'success'): ?>
                            <div class="mt-2 space-y-1 text-xs">
                                <?php if($latestDisbursement->transaction_receipt): ?>
                                    <p class="text-slate-700"><span class="font-medium">Receipt:</span> <?php echo e($latestDisbursement->transaction_receipt); ?></p>
                                <?php endif; ?>
                                <?php if($latestDisbursement->mpesa_conversation_id): ?>
                                    <p class="text-slate-700"><span class="font-medium">Conversation ID:</span> <?php echo e($latestDisbursement->mpesa_conversation_id); ?></p>
                                <?php endif; ?>
                                <p class="text-emerald-700 font-medium">✓ Payment successfully sent via M-Pesa B2C</p>
                            </div>
                        <?php elseif($latestDisbursement->status === 'pending'): ?>
                            <div class="mt-2 space-y-1 text-xs">
                                <?php if($latestDisbursement->mpesa_request_id): ?>
                                    <p class="text-slate-700"><span class="font-medium">Request ID:</span> <?php echo e($latestDisbursement->mpesa_request_id); ?></p>
                                <?php endif; ?>
                                <?php if($latestDisbursement->mpesa_response_description): ?>
                                    <p class="text-slate-700"><span class="font-medium">Response:</span> <?php echo e($latestDisbursement->mpesa_response_description); ?></p>
                                <?php endif; ?>
                                <p class="text-yellow-700 font-medium">⏳ Payment initiated, awaiting M-Pesa callback confirmation</p>
                            </div>
                        <?php elseif($latestDisbursement->status === 'failed'): ?>
                            <div class="mt-2 space-y-1 text-xs">
                                <?php if($latestDisbursement->mpesa_result_description): ?>
                                    <p class="text-rose-700"><span class="font-medium">Error:</span> <?php echo e($latestDisbursement->mpesa_result_description); ?></p>
                                <?php elseif($latestDisbursement->mpesa_response_description): ?>
                                    <p class="text-rose-700"><span class="font-medium">Error:</span> <?php echo e($latestDisbursement->mpesa_response_description); ?></p>
                                <?php endif; ?>
                                <p class="text-rose-700 font-medium">✗ Payment failed. Please check the error message above.</p>
                            </div>
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
    <?php endif; ?>

    <?php if($loanApplication->loan && $loanApplication->loan->instalments->isNotEmpty()): ?>
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['class' => 'xl:col-span-3 mt-6','title' => 'Repayment Schedule','description' => 'Projected instalments from first to final payment']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'xl:col-span-3 mt-6','title' => 'Repayment Schedule','description' => 'Projected instalments from first to final payment']); ?>
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">#</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Due Date</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Principal</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Interest</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Total</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__currentLoopData = $loanApplication->loan->instalments->sortBy('due_date')->values(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $instalment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="text-slate-700">
                                <td class="px-4 py-2 text-xs font-semibold text-slate-500"><?php echo e($index + 1); ?></td>
                                <td class="px-4 py-2"><?php echo e(optional($instalment->due_date)->format('d M Y')); ?></td>
                                <td class="px-4 py-2 text-right">KES <?php echo e(number_format($instalment->principal_amount, 2)); ?></td>
                                <td class="px-4 py-2 text-right">KES <?php echo e(number_format($instalment->interest_amount, 2)); ?></td>
                                <td class="px-4 py-2 text-right font-semibold text-slate-900">KES <?php echo e(number_format($instalment->total_amount, 2)); ?></td>
                                <td class="px-4 py-2">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                            'bg-emerald-50 text-emerald-700' => $instalment->status === 'paid',
                                            'bg-amber-50 text-amber-700' => $instalment->status === 'pending',
                                            'bg-rose-50 text-rose-700' => $instalment->status === 'overdue',
                                            'bg-slate-100 text-slate-500' => !in_array($instalment->status, ['paid','pending','overdue']),
                                        ]); ?>"
                                    ">
                                        <?php echo e(ucfirst($instalment->status)); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
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
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', ['title' => 'Approval · ' . $loanApplication->application_number], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views/admin/approvals/show.blade.php ENDPATH**/ ?>