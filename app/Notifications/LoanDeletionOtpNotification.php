<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanDeletionOtpNotification extends Notification
{
    use Queueable;

    public function __construct(
        private Loan $loan,
        private string $otp
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $client = $this->loan->client;

        return (new MailMessage)
            ->subject('Verification code to delete loan #' . $this->loan->id)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You requested to permanently delete a loan record in Wilchar LMS.')
            ->line('**Loan ID:** ' . $this->loan->id)
            ->line('**Client:** ' . ($client?->full_name ?? '—'))
            ->line('**One-time code:** **' . $this->otp . '**')
            ->line('Enter this 6-digit code on the loan page within 10 minutes to confirm deletion.')
            ->action('Open loan', route('loans.show', $this->loan))
            ->line('If you did not request this, secure your account and contact an administrator.');
    }
}
