<?php

namespace App\Console\Commands;

use App\Mail\LoanReminderMail;
use App\Models\AuditLog;
use App\Models\Loan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendLoanRemindersCommand extends Command
{
    protected $signature = 'loans:send-reminders';

    protected $description = 'Send email reminders for upcoming and due loan instalments';

    public function handle(): int
    {
        $this->info('Sending loan reminders...');

        $this->sendUpcomingReminders();
        $this->sendDueReminders();

        $this->info('Loan reminders dispatched.');

        return self::SUCCESS;
    }

    private function sendUpcomingReminders(): void
    {
        $tomorrow = now()->addDay()->toDateString();

        Loan::with('client')
            ->whereIn('status', ['approved', 'disbursed'])
            ->whereDate('next_due_date', '=', $tomorrow)
            ->where('outstanding_balance', '>', 0)
            ->each(function (Loan $loan) {
                if (!$loan->client?->email) {
                    return;
                }

                Mail::to($loan->client->email)->queue(new LoanReminderMail($loan, 'upcoming'));

                AuditLog::log(
                    Loan::class,
                    $loan->id,
                    'reminder_upcoming',
                    'Upcoming instalment reminder emailed to client.'
                );
            });
    }

    private function sendDueReminders(): void
    {
        $today = now()->toDateString();

        Loan::with('client')
            ->whereIn('status', ['approved', 'disbursed'])
            ->whereDate('next_due_date', '=', $today)
            ->where('outstanding_balance', '>', 0)
            ->each(function (Loan $loan) {
                if (!$loan->client?->email) {
                    return;
                }

                Mail::to($loan->client->email)->queue(new LoanReminderMail($loan, 'due'));

                AuditLog::log(
                    Loan::class,
                    $loan->id,
                    'reminder_due',
                    'Due instalment reminder emailed to client.'
                );
            });
    }
}

