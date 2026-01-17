<?php
    $client = $loanApplication->client;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loan Approval Required</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f7fb;
            color: #0f172a;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            width: 100%;
            padding: 32px 0;
        }
        .container {
            max-width: 560px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #059669, #10b981);
            color: #ecfdf5;
            padding: 32px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }
        .content {
            padding: 32px;
        }
        .content h2 {
            margin-top: 0;
            font-size: 18px;
        }
        .details {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            margin: 24px 0;
            background-color: #f8fafc;
        }
        .details p {
            margin: 6px 0;
            font-size: 14px;
        }
        .cta {
            display: inline-block;
            background-color: #059669;
            color: #ffffff !important;
            padding: 14px 26px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 600;
            letter-spacing: 0.02em;
            margin-top: 8px;
        }
        .footer {
            padding: 24px 32px 34px;
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header" style="background: linear-gradient(135deg, <?php echo e($action === 'rejection' ? '#dc2626, #ef4444' : '#059669, #10b981'); ?>);">
                <p style="margin:0;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;"><?php echo e($action === 'rejection' ? 'Application Rejected' : 'Loan approval needed'); ?></p>
                <h1>Hi <?php echo e($notifiable->name); ?>,</h1>
                <p style="margin:8px 0 0;font-size:14px;opacity:0.85;"><?php echo e($action === 'rejection' ? 'Application rejected at ' . $stageDisplay : $stageDisplay . ' for ' . $loanApplication->application_number); ?></p>
            </div>
            <div class="content">
                <h2><?php echo e($action === 'rejection' ? 'Application has been rejected' : 'Application ready for your review'); ?></h2>
                <p>
                    <?php if($action === 'rejection'): ?>
                        The loan application below has been rejected at the <?php echo e($stageDisplay); ?> stage. Please review the details and rejection reason in the admin panel.
                    <?php else: ?>
                        The loan application below has entered your queue. Please log into the admin panel to review the supporting documents and take the next action.
                    <?php endif; ?>
                </p>

                <div class="details">
                    <p><strong>Client:</strong> <?php echo e($client->full_name); ?> (<?php echo e($client->phone); ?>)</p>
                    <p><strong>Amount Requested:</strong> KES <?php echo e(number_format($loanApplication->amount, 2)); ?></p>
                    <p><strong>Current Stage:</strong> <?php echo e($stageDisplay); ?></p>
                    <p><strong>Submitted:</strong> <?php echo e($loanApplication->created_at?->format('d M Y, H:i')); ?></p>
                    <?php if($action === 'rejection' && $loanApplication->rejection_reason): ?>
                        <p><strong>Rejection Reason:</strong> <?php echo e($loanApplication->rejection_reason); ?></p>
                    <?php endif; ?>
                    <?php if($loanApplication->team?->name): ?>
                        <p><strong>Team:</strong> <?php echo e($loanApplication->team->name); ?></p>
                    <?php endif; ?>
                </div>

                <a href="<?php echo e($ctaUrl); ?>" class="cta" style="background-color: <?php echo e($action === 'rejection' ? '#dc2626' : '#059669'); ?>;"><?php echo e($action === 'rejection' ? 'View Application Details' : 'Review in Admin Panel'); ?></a>
                <p style="font-size:12px;color:#94a3b8;margin-top:16px;">If the button does not work, copy this link into your browser: <br><?php echo e($ctaUrl); ?></p>
            </div>
            <div class="footer">
                <?php echo e(config('app.name')); ?> • Automated approval notification<br>
                You’re receiving this email because your role is required in the approval workflow.
            </div>
        </div>
    </div>
</body>
</html>

<?php /**PATH C:\xampp\htdocs\wilchar\resources\views/emails/approvals/stage-notification.blade.php ENDPATH**/ ?>