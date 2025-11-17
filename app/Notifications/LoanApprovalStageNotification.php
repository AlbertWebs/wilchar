<?php

namespace App\Notifications;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanApprovalStageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private LoanApplication $loanApplication,
        private string $action = 'approval' // 'approval' or 'rejection'
    ) {
        $this->loanApplication->loadMissing('client');
    }

    public function via(object $notifiable): array
    {
        // Check if user has permission to view approvals
        if (!$notifiable->hasRole('Admin') && !$notifiable->can('approvals.view')) {
            // Check if user has the required role for the current stage
            $stage = $this->loanApplication->approval_stage;
            $hasRequiredRole = match ($stage) {
                'loan_officer' => $notifiable->hasRole('Loan Officer') || $notifiable->hasRole('Marketer'),
                'credit_officer' => $notifiable->hasRole('Credit Officer'),
                'finance_officer' => $notifiable->hasRole('Finance'),
                'director' => $notifiable->hasRole('Director'),
                default => false,
            };

            if (!$hasRequiredRole) {
                return []; // Don't send notification if user doesn't have permission
            }
        }

        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $loan = $this->loanApplication;
        $stageDisplay = $loan->current_stage_display ?? ucfirst(str_replace('_', ' ', $loan->approval_stage));
        $url = route('approvals.show', $loan);
        
        $subject = $this->action === 'rejection' 
            ? "Application Rejected · {$loan->application_number}"
            : "Approval needed · {$loan->application_number}";

        $mailMessage = (new MailMessage)
            ->subject($subject)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->cc('admin@nurusmesolution.com')
            ->view('emails.approvals.stage-notification', [
                'loanApplication' => $loan,
                'notifiable' => $notifiable,
                'stageDisplay' => $stageDisplay,
                'ctaUrl' => $url,
                'action' => $this->action,
            ]);

        return $mailMessage;
    }
}

