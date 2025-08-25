<div class="form-group">
    <label for="client_id">Client</label>
    <select name="client_id" id="client_id" class="form-control" required>
        <option value="">-- Select Client --</option>
        @foreach($clients as $client)
            <option value="{{ $client->id }}" {{ old('client_id', $loan->client_id ?? '') == $client->id ? 'selected' : '' }}>
                {{ $client->first_name }} {{ $client->last_name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="loan_type">Loan Type</label>
    <input type="text" name="loan_type" class="form-control" value="{{ old('loan_type', $loan->loan_type ?? '') }}" required>
</div>

<div class="form-group">
    <label for="amount_requested">Amount Requested</label>
    <input type="number" step="0.01" name="amount_requested" class="form-control" value="{{ old('amount_requested', $loan->amount_requested ?? '') }}" required>
</div>

<div class="form-group">
    <label for="amount_approved">Amount Approved</label>
    <input type="number" step="0.01" name="amount_approved" class="form-control" value="{{ old('amount_approved', $loan->amount_approved ?? '') }}">
</div>

<div class="form-group">
    <label for="term_months">Term (Months)</label>
    <input type="number" name="term_months" class="form-control" value="{{ old('term_months', $loan->term_months ?? '') }}" required>
</div>

<div class="form-group">
    <label for="interest_rate">Interest Rate (%)</label>
    <input type="number" step="0.01" name="interest_rate" class="form-control" value="{{ old('interest_rate', $loan->interest_rate ?? '') }}" required>
</div>

<div class="form-group">
    <label for="repayment_frequency">Repayment Frequency</label>
    <select name="repayment_frequency" class="form-control" required>
        <option value="">-- Select Frequency --</option>
        @foreach(['monthly', 'weekly', 'biweekly'] as $freq)
            <option value="{{ $freq }}" {{ old('repayment_frequency', $loan->repayment_frequency ?? '') == $freq ? 'selected' : '' }}>
                {{ ucfirst($freq) }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="status">Status</label>
    <select name="status" class="form-control" required>
        @foreach(['pending', 'approved', 'rejected', 'disbursed', 'closed'] as $status)
            <option value="{{ $status }}" {{ old('status', $loan->status ?? '') == $status ? 'selected' : '' }}>
                {{ ucfirst($status) }}
            </option>
        @endforeach
    </select>
</div>
