@php
    $isEdit = isset($loanProduct);
@endphp

<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Product Name</label>
            <input type="text" name="name" value="{{ old('name', $loanProduct->name ?? '') }}" class="mt-1 w-full rounded-xl border-slate-200" required>
        </div>
        <div>
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
            <select name="is_active" class="mt-1 w-full rounded-xl border-slate-200">
                <option value="1" @selected(old('is_active', $loanProduct->is_active ?? true))>Active</option>
                <option value="0" @selected(!old('is_active', $loanProduct->is_active ?? true))>Disabled</option>
            </select>
        </div>
    </div>

    <div>
        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Description</label>
        <textarea name="description" rows="3" class="mt-1 w-full rounded-xl border-slate-200">{{ old('description', $loanProduct->description ?? '') }}</textarea>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div>
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Base Interest (%)</label>
            <input type="number" step="0.01" min="0" max="100" name="base_interest_rate" value="{{ old('base_interest_rate', $loanProduct->base_interest_rate ?? 0) }}" class="mt-1 w-full rounded-xl border-slate-200" required>
        </div>
        <div>
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Monthly Rate (%)</label>
            <input type="number" step="0.01" min="0" max="100" name="interest_rate_per_month" value="{{ old('interest_rate_per_month', $loanProduct->interest_rate_per_month ?? 0) }}" class="mt-1 w-full rounded-xl border-slate-200" required>
        </div>
        <div>
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Processing Fee (%)</label>
            <input type="number" step="0.01" min="0" max="100" name="processing_fee_rate" value="{{ old('processing_fee_rate', $loanProduct->processing_fee_rate ?? 0) }}" class="mt-1 w-full rounded-xl border-slate-200" required>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Minimum Amount (KES)</label>
            <input type="number" step="0.01" min="0" name="min_amount" value="{{ old('min_amount', $loanProduct->min_amount ?? '') }}" class="mt-1 w-full rounded-xl border-slate-200">
        </div>
        <div>
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Maximum Amount (KES)</label>
            <input type="number" step="0.01" min="0" name="max_amount" value="{{ old('max_amount', $loanProduct->max_amount ?? '') }}" class="mt-1 w-full rounded-xl border-slate-200">
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Minimum Duration (Months)</label>
            <input type="number" min="1" max="120" name="min_duration_months" value="{{ old('min_duration_months', $loanProduct->min_duration_months ?? 1) }}" class="mt-1 w-full rounded-xl border-slate-200" required>
        </div>
        <div>
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Maximum Duration (Months)</label>
            <input type="number" min="1" max="120" name="max_duration_months" value="{{ old('max_duration_months', $loanProduct->max_duration_months ?? 12) }}" class="mt-1 w-full rounded-xl border-slate-200" required>
        </div>
    </div>
</div>

