<?php

namespace App\Notifications;

use App\Models\Disbursement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DisbursementOtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Disbursement $disbursement, private string $otp)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Disbursement OTP for ' . ($this->disbursement->loanApplication->application_number ?? 'Loan'))
            ->greeting('Hello ' . $notifiable->name)
            ->line('Finance has prepared a loan disbursement that requires your authorization.')
            ->line('Loan Application: ' . ($this->disbursement->loanApplication->application_number ?? $this->disbursement->loan_application_id))
            ->line('Amount: KES ' . number_format($this->disbursement->amount, 2))
            ->line('One-Time Password (OTP): **' . $this->otp . '**')
            ->line('This code expires in 10 minutes. Enter it on the disbursement confirmation screen to release funds.')
            ->action('Review Disbursement', route('finance-disbursements.confirm.show', $this->disbursement))
            ->line('If you did not request this action, please inform the system administrator immediately.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'disbursement_id' => $this->disbursement->id,
            'loan_application' => $this->disbursement->loanApplication->application_number ?? $this->disbursement->loan_application_id,
            'amount' => $this->disbursement->amount,
        ];
    }
}

