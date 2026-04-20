<?php $__env->startComponent('mail::message'); ?>
# <?php echo e($type === 'due' ? 'Loan Instalment Due Today' : 'Upcoming Loan Instalment'); ?>


Hello <?php echo e($loan->client->full_name); ?>,

This is a friendly reminder regarding your loan **<?php echo e($loan->loan_type); ?>** with an outstanding balance of **KES <?php echo e(number_format($loan->outstanding_balance, 2)); ?>**.

<?php if($type === 'due'): ?>
- Your instalment is **due today (<?php echo e($loan->next_due_date?->format('d M Y')); ?>)**.
<?php else: ?>
- Your instalment is due on **<?php echo e($loan->next_due_date?->format('d M Y')); ?>**.
<?php endif; ?>
- Approved Amount: **KES <?php echo e(number_format($loan->amount_approved, 2)); ?>**
- Total Repayable: **KES <?php echo e(number_format($loan->total_amount, 2)); ?>**
- Outstanding Balance: **KES <?php echo e(number_format($loan->outstanding_balance, 2)); ?>**

Please ensure payment is made promptly to avoid penalties. If payment has already been made, kindly disregard this message.

<?php $__env->startComponent('mail::button', ['url' => config('app.url')]); ?>
Login to Dashboard
<?php echo $__env->renderComponent(); ?>

Regards,<br>
<?php echo e(config('app.name')); ?> Finance Team
<?php echo $__env->renderComponent(); ?>

<?php /**PATH C:\projects\wilchar\resources\views\emails\loan_reminder.blade.php ENDPATH**/ ?>