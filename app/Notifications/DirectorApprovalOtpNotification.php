<?php

namespace App\Notifications;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DirectorApprovalOtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private LoanApplication $loanApplication,
        private string $otp,
        private float $amount
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('OTP Required for Loan Approval - ' . $this->loanApplication->application_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A loan application requires your final approval and disbursement authorization.')
            ->line('**Loan Application:** ' . $this->loanApplication->application_number)
            ->line('**Client:** ' . $this->loanApplication->client->full_name)
            ->line('**Amount:** KES ' . number_format($this->amount, 2))
            ->line('**One-Time Password (OTP):** **' . $this->otp . '**')
            ->line('This code expires in 10 minutes. Enter it on the approval page to complete the disbursement.')
            ->action('Review Application', route('approvals.show', $this->loanApplication))
            ->line('If you did not request this action, please inform the system administrator immediately.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'loan_application_id' => $this->loanApplication->id,
            'application_number' => $this->loanApplication->application_number,
            'amount' => $this->amount,
        ];
    }
}
