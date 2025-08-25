@extends('adminlte::page')

@section('title', 'Loan Applications')

@section('content_header')
    <h1>Loan Applications</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List of Loan Applications</h3>
            <div class="card-tools">
                <a href="{{ route('loan-applications.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> New Application
                </a>
            </div>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Loan Type</th>
                        <th>Amount Requested</th>
                        <th>Amount Approved</th>
                        <th>Term (Months)</th>
                        <th>Interest Rate (%)</th>
                        <th>Repayment Frequency</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr>
                            <td>{{ $loan->id }}</td>
                            <td>{{ $loan->client->first_name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($loan->loan_type) }}</td>
                            <td>{{ number_format($loan->amount_requested, 2) }}</td>
                            <td>{{ $loan->amount_approved ? number_format($loan->amount_approved, 2) : '-' }}</td>
                            <td>{{ $loan->term_months }}</td>
                            <td>{{ number_format($loan->interest_rate, 2) }}</td>
                            <td>{{ ucfirst($loan->repayment_frequency) }}</td>
                            <td>
                                <span class="badge badge-{{ 
                                    $loan->status === 'approved' ? 'success' : 
                                    ($loan->status === 'pending' ? 'warning' : 
                                    ($loan->status === 'rejected' ? 'danger' : 'info')) 
                                }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td>{{ $loan->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('loan-applications.show', $loan->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('loan-applications.edit', $loan->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('loan-applications.destroy', $loan->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this loan application?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted">No loan applications found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
