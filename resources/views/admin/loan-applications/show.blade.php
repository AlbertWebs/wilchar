@extends('adminlte::page')

@section('title', 'Loan Application Details')

@section('content_header')
    <h1>Loan Application #{{ $loan->id }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <p><strong>Client:</strong> {{ $loan->client->first_name ?? $loan->client->name ?? 'N/A' }}</p>
        <p><strong>Loan Type:</strong> {{ ucfirst($loan->loan_type) }}</p>
        <p><strong>Amount Requested:</strong> {{ number_format($loan->amount_requested, 2) }}</p>
        <p><strong>Amount Approved:</strong> {{ $loan->amount_approved ? number_format($loan->amount_approved, 2) : '-' }}</p>
        <p><strong>Term:</strong> {{ $loan->term_months }} months</p>
        <p><strong>Interest Rate:</strong> {{ number_format($loan->interest_rate, 2) }}%</p>
        <p><strong>Repayment Frequency:</strong> {{ ucfirst($loan->repayment_frequency) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($loan->status) }}</p>
        <p><strong>Submitted At:</strong> {{ $loan->created_at->format('Y-m-d H:i') }}</p>
    </div>
</div>
@stop

