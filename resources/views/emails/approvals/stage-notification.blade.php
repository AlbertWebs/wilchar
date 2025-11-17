@php
    $client = $loanApplication->client;
@endphp

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
            <div class="header" style="background: linear-gradient(135deg, {{ $action === 'rejection' ? '#dc2626, #ef4444' : '#059669, #10b981' }});">
                <p style="margin:0;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;">{{ $action === 'rejection' ? 'Application Rejected' : 'Loan approval needed' }}</p>
                <h1>Hi {{ $notifiable->name }},</h1>
                <p style="margin:8px 0 0;font-size:14px;opacity:0.85;">{{ $action === 'rejection' ? 'Application rejected at ' . $stageDisplay : $stageDisplay . ' for ' . $loanApplication->application_number }}</p>
            </div>
            <div class="content">
                <h2>{{ $action === 'rejection' ? 'Application has been rejected' : 'Application ready for your review' }}</h2>
                <p>
                    @if($action === 'rejection')
                        The loan application below has been rejected at the {{ $stageDisplay }} stage. Please review the details and rejection reason in the admin panel.
                    @else
                        The loan application below has entered your queue. Please log into the admin panel to review the supporting documents and take the next action.
                    @endif
                </p>

                <div class="details">
                    <p><strong>Client:</strong> {{ $client->full_name }} ({{ $client->phone }})</p>
                    <p><strong>Amount Requested:</strong> KES {{ number_format($loanApplication->amount, 2) }}</p>
                    <p><strong>Current Stage:</strong> {{ $stageDisplay }}</p>
                    <p><strong>Submitted:</strong> {{ $loanApplication->created_at?->format('d M Y, H:i') }}</p>
                    @if($action === 'rejection' && $loanApplication->rejection_reason)
                        <p><strong>Rejection Reason:</strong> {{ $loanApplication->rejection_reason }}</p>
                    @endif
                    @if($loanApplication->team?->name)
                        <p><strong>Team:</strong> {{ $loanApplication->team->name }}</p>
                    @endif
                </div>

                <a href="{{ $ctaUrl }}" class="cta" style="background-color: {{ $action === 'rejection' ? '#dc2626' : '#059669' }};">{{ $action === 'rejection' ? 'View Application Details' : 'Review in Admin Panel' }}</a>
                <p style="font-size:12px;color:#94a3b8;margin-top:16px;">If the button does not work, copy this link into your browser: <br>{{ $ctaUrl }}</p>
            </div>
            <div class="footer">
                {{ config('app.name') }} • Automated approval notification<br>
                You’re receiving this email because your role is required in the approval workflow.
            </div>
        </div>
    </div>
</body>
</html>

