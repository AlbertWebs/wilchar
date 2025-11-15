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

    public function __construct(private LoanApplication $loanApplication)
    {
        $this->loanApplication->loadMissing('client');
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $loan = $this->loanApplication;
        $stageDisplay = $loan->current_stage_display ?? ucfirst(str_replace('_', ' ', $loan->approval_stage));
        $url = route('approvals.show', $loan);

        return (new MailMessage)
            ->subject("Approval needed · {$loan->application_number}")
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.approvals.stage-notification', [
                'loanApplication' => $loan,
                'notifiable' => $notifiable,
                'stageDisplay' => $stageDisplay,
                'ctaUrl' => $url,
            ]);
    }
}

