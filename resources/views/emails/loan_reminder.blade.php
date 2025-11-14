@component('mail::message')
# {{ $type === 'due' ? 'Loan Instalment Due Today' : 'Upcoming Loan Instalment' }}

Hello {{ $loan->client->full_name }},

This is a friendly reminder regarding your loan **{{ $loan->loan_type }}** with an outstanding balance of **KES {{ number_format($loan->outstanding_balance, 2) }}**.

@if($type === 'due')
- Your instalment is **due today ({{ $loan->next_due_date?->format('d M Y') }})**.
@else
- Your instalment is due on **{{ $loan->next_due_date?->format('d M Y') }}**.
@endif
- Approved Amount: **KES {{ number_format($loan->amount_approved, 2) }}**
- Total Repayable: **KES {{ number_format($loan->total_amount, 2) }}**
- Outstanding Balance: **KES {{ number_format($loan->outstanding_balance, 2) }}**

Please ensure payment is made promptly to avoid penalties. If payment has already been made, kindly disregard this message.

@component('mail::button', ['url' => config('app.url')])
Login to Dashboard
@endcomponent

Regards,<br>
{{ config('app.name') }} Finance Team
@endcomponent

