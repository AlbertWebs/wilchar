<?php

namespace App\Mail;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Loan $loan,
        public string $type = 'upcoming'
    ) {
    }

    public function build(): self
    {
        $subject = $this->type === 'due'
            ? 'Loan Due Reminder'
            : 'Upcoming Loan Instalment Reminder';

        return $this->subject($subject)
            ->markdown('emails.loan_reminder', [
                'loan' => $this->loan,
                'type' => $this->type,
            ]);
    }
}

