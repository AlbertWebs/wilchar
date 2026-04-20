<?php $__env->startSection('header'); ?>
    <?php echo e($client->full_name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <p class="text-base font-semibold text-slate-900"><?php echo e($client->full_name); ?></p>
                <p class="text-sm text-slate-500">Client Code: <?php echo e($client->client_code); ?></p>
            </div>
            <div class="flex items-center gap-3">
                <a
                    href="<?php echo e(route('admin.clients.edit', $client)); ?>"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Client
                </a>
                <a
                    href="<?php echo e(route('admin.clients.index')); ?>"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50"
                >
                    Back to List
                </a>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['class' => 'xl:col-span-2','title' => 'Client Profile','description' => 'Personal and business information']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'xl:col-span-2','title' => 'Client Profile','description' => 'Personal and business information']); ?>
                <div class="space-y-6">
                    <!-- Personal Information -->
                    <div class="rounded-xl border border-slate-200 bg-white p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-4">Personal Information</p>
                        <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Full Name</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($client->full_name); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">ID Number</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($client->id_number); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Date of Birth</dt>
                                <dd class="mt-1 text-slate-700"><?php echo e($client->date_of_birth ? $client->date_of_birth->format('M d, Y') : '—'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Gender</dt>
                                <dd class="mt-1 text-slate-700"><?php echo e(ucfirst($client->gender ?? '—')); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Nationality</dt>
                                <dd class="mt-1 text-slate-700"><?php echo e($client->nationality ?? '—'); ?></dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Contact Information -->
                    <div class="rounded-xl border border-slate-200 bg-white p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-4">Contact Information</p>
                        <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Phone</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($client->phone); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Email</dt>
                                <dd class="mt-1 text-slate-700"><?php echo e($client->email ?? '—'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">M-PESA Phone</dt>
                                <dd class="mt-1 text-slate-700"><?php echo e($client->mpesa_phone ?? '—'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Alternate Phone</dt>
                                <dd class="mt-1 text-slate-700"><?php echo e($client->alternate_phone ?? '—'); ?></dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Address</dt>
                                <dd class="mt-1 text-slate-700"><?php echo e($client->address ?? '—'); ?></dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Business Information -->
                    <div class="rounded-xl border border-slate-200 bg-white p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-4">Business Information</p>
                        <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                            <div class="md:col-span-2">
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Business Name</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($client->business_name); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Business Type</dt>
                                <dd class="mt-1 text-slate-700"><?php echo e($client->business_type); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Location</dt>
                                <dd class="mt-1 text-slate-700"><?php echo e($client->location); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Occupation</dt>
                                <dd class="mt-1 text-slate-700"><?php echo e($client->occupation ?? '—'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Employer</dt>
                                <dd class="mt-1 text-slate-700"><?php echo e($client->employer ?? '—'); ?></dd>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Status & Activity','description' => 'Client status and related information']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Status & Activity','description' => 'Client status and related information']); ?>
                <div class="space-y-4">
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-3">Status</p>
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                            <?php echo e($client->status === 'active' ? 'bg-emerald-50 text-emerald-600' : ($client->status === 'blacklisted' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600')); ?>">
                            <?php echo e(ucfirst($client->status ?? 'inactive')); ?>

                        </span>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-3">KYC Status</p>
                        <div class="flex items-center gap-2">
                            <?php if($client->kyc_completed): ?>
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                    <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Completed
                                </span>
                                <?php if($client->kyc_completed_at): ?>
                                    <span class="text-xs text-slate-500"><?php echo e($client->kyc_completed_at->format('M d, Y')); ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-600">
                                    Pending
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if($client->credit_score): ?>
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-3">Credit Score</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($client->credit_score); ?></p>
                        <?php if($client->credit_score_updated_at): ?>
                            <p class="text-xs text-slate-500 mt-1">Updated <?php echo e($client->credit_score_updated_at->diffForHumans()); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-3">Loan Activity</p>
                        <dl class="space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-600">Total Loans</dt>
                                <dd class="font-semibold text-slate-900"><?php echo e($client->loans()->count()); ?></dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-600">Applications</dt>
                                <dd class="font-semibold text-slate-900"><?php echo e($client->loanApplications()->count()); ?></dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-3">Account Information</p>
                        <dl class="space-y-2 text-sm text-slate-700">
                            <div>
                                <dt class="text-slate-500">Created</dt>
                                <dd class="mt-1 font-semibold text-slate-900"><?php echo e($client->created_at->format('M d, Y')); ?></dd>
                            </div>
                            <?php if($client->created_by): ?>
                            <div>
                                <dt class="text-slate-500">Created By</dt>
                                <dd class="mt-1 text-slate-700"><?php echo e($client->created_by); ?></dd>
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

        <?php if($client->loans()->count() > 0 || $client->loanApplications()->count() > 0): ?>
        <?php if (isset($component)) { $__componentOriginal48bdbafd2a1d96966a35067215fb52b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48bdbafd2a1d96966a35067215fb52b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.section','data' => ['title' => 'Loan History','description' => 'All loans and applications for this client']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Loan History','description' => 'All loans and applications for this client']); ?>
            <div class="space-y-4">
                <?php if($client->loanApplications()->count() > 0): ?>
                <div>
                    <p class="text-sm font-semibold text-slate-900 mb-3">Loan Applications (<?php echo e($client->loanApplications()->count()); ?>)</p>
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3 text-left">Application #</th>
                                    <th class="px-4 py-3 text-left">Amount</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-left">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $__currentLoopData = $client->loanApplications()->latest()->take(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3">
                                        <a href="<?php echo e(route('loan-applications.show', $application)); ?>" class="font-semibold text-emerald-600 hover:text-emerald-700">
                                            <?php echo e($application->application_number ?? 'APP-' . $application->id); ?>

                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">KES <?php echo e(number_format($application->amount, 2)); ?></td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-2 py-1 text-xs font-semibold
                                            <?php echo e($application->status === 'approved' ? 'bg-emerald-50 text-emerald-600' : ($application->status === 'rejected' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600')); ?>">
                                            <?php echo e(ucfirst($application->status)); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600"><?php echo e($application->created_at->format('M d, Y')); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($client->loans()->count() > 0): ?>
                <div>
                    <p class="text-sm font-semibold text-slate-900 mb-3">Active Loans (<?php echo e($client->loans()->count()); ?>)</p>
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3 text-left">Loan Type</th>
                                    <th class="px-4 py-3 text-left">Amount</th>
                                    <th class="px-4 py-3 text-left">Outstanding</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $__currentLoopData = $client->loans()->latest()->take(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3">
                                        <a href="<?php echo e(route('loans.show', $loan)); ?>" class="font-semibold text-emerald-600 hover:text-emerald-700">
                                            <?php echo e($loan->loan_type); ?>

                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">KES <?php echo e(number_format($loan->amount_approved, 2)); ?></td>
                                    <td class="px-4 py-3 font-semibold text-rose-600">KES <?php echo e(number_format($loan->outstanding_balance, 2)); ?></td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-2 py-1 text-xs font-semibold bg-emerald-50 text-emerald-600">
                                            <?php echo e(ucfirst($loan->status)); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
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
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', ['title' => $client->full_name], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projects\wilchar\resources\views\admin\clients\show.blade.php ENDPATH**/ ?>