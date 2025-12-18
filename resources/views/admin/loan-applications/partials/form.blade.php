@php
    $isEdit = $mode === 'edit';
    $action = $isEdit ? route('loan-applications.update', $application) : route('loan-applications.store');
    $productOptions = $loanProducts->map(function ($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'baseRate' => (float) $product->base_interest_rate,
            'monthlyRate' => (float) $product->interest_rate_per_month,
            'processingRate' => (float) $product->processing_fee_rate,
        ];
    });
@endphp

<form
    x-data="{
        amount: {{ old('amount', $application->amount ?? 0) }},
        duration: {{ old('duration_months', $application->duration_months ?? 1) }},
        registrationFee: {{ old('registration_fee', $application->registration_fee ?? 0) }},
        interestRate: {{ old('interest_rate', $application->interest_rate ?? 35) }},
        products: @js($productOptions),
        selectedProductId: {{ old('loan_product_id', $application->loan_product_id ?? 'null') }},
        frequencyOptions: @js(collect($repaymentFrequencies)->map(fn($freq, $key) => array_merge($freq, ['key' => $key]))->values()),
        selectedFrequencyKey: '{{ old('repayment_frequency', $application->repayment_frequency ?? 'monthly') }}',
        repaymentIntervalWeeks: {{ old('repayment_interval_weeks', $application->repayment_interval_weeks ?? 4) }},
        weeklyPayment: {{ old('weekly_payment_amount', $application->weekly_payment_amount ?? 0) }},
        cyclePayment: {{ old('repayment_cycle_amount', $application->repayment_cycle_amount ?? 0) }},
        totalInterest: {{ old('interest_amount', $application->interest_amount ?? 0) }},
        totalRepayment: {{ old('total_repayment_amount', $application->total_repayment_amount ?? 0) }},
        updateFromProduct() {
            const product = this.products.find(p => p.id == this.selectedProductId);
            if (!product) return;
            this.interestRate = product.baseRate + (product.monthlyRate * Math.max(this.duration - 1, 0));
            this.calculateTotals();
        },
        applyFrequency() {
            const option = this.frequencyOptions.find(opt => opt.key === this.selectedFrequencyKey) || this.frequencyOptions[0];
            if (!option) {
                return;
            }
            this.interestRate = option.rate;
            this.repaymentIntervalWeeks = option.weeks;
            this.calculateTotals();
        },
        calculateTotals() {
            const amount = parseFloat(this.amount) || 0;
            const durationMonths = Math.max(1, parseInt(this.duration) || 1);
            const totalWeeks = Math.max(1, Math.ceil(durationMonths * 4));
            const intervalWeeks = Math.max(1, parseInt(this.repaymentIntervalWeeks) || 1);
            const intervalCount = Math.max(1, Math.ceil(totalWeeks / intervalWeeks));
            const rate = parseFloat(this.interestRate) || 0;
            const registration = parseFloat(this.registrationFee) || 0;
            const interest = ((rate / 100) * amount) * intervalCount;
            this.totalInterest = Math.round((interest + Number.EPSILON) * 100) / 100;
            this.totalRepayment = Math.round((amount + this.totalInterest + registration + Number.EPSILON) * 100) / 100;
            this.weeklyPayment = Math.round(((this.totalRepayment / totalWeeks) + Number.EPSILON) * 100) / 100;
            this.cyclePayment = Math.round(((this.totalRepayment / intervalCount) + Number.EPSILON) * 100) / 100;
        }
    }"
    x-init="applyFrequency();"
    x-ajax="{
        successMessage: { title: 'Saved', text: 'Loan application saved successfully.' },
        onSuccess(response) {
            window.dispatchEvent(new CustomEvent('loan-applications:refresh'));
            if (window.Alpine?.store('modal')) {
                window.Alpine.store('modal').close();
            }
        }
    }"
    action="{{ $action }}"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-6"
>
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="space-y-6">
        @if($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                <p class="font-semibold">Please fix the following before submitting:</p>
                <ul class="mt-2 list-disc pl-5 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <p class="text-base font-semibold text-slate-900">Borrower & Business</p>
                    <p class="text-sm text-slate-500">Capture client identity and business background.</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Step 1</span>
            </div>
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div>
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-slate-700">Client</label>
                        <a
                            href="{{ route('admin.clients.create') }}"
                            target="_blank"
                            class="text-xs font-semibold text-emerald-600 hover:text-emerald-700"
                        >
                            + New Client
                        </a>
                    </div>
                    <select name="client_id" class="mt-1 w-full rounded-xl border-slate-200" required>
                        <option value="">Select client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected(old('client_id', $application->client_id ?? '') == $client->id)>
                                {{ $client->full_name }} · {{ $client->phone }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Team</label>
                    <select name="team_id" class="mt-1 w-full rounded-xl border-slate-200">
                        <option value="">Assign to team</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" @selected(old('team_id', $application->team_id ?? '') == $team->id)>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Business Type</label>
                    <input type="text" name="business_type" value="{{ old('business_type', $application->business_type ?? '') }}" class="mt-1 w-full rounded-xl border-slate-200 @error('business_type') border-rose-400 focus:border-rose-400 focus:ring-rose-300 @enderror" placeholder="e.g. Retail, Agri-business" required>
                    @error('business_type')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Business Location</label>
                    <input type="text" name="business_location" value="{{ old('business_location', $application->business_location ?? '') }}" class="mt-1 w-full rounded-xl border-slate-200 @error('business_location') border-rose-400 focus:border-rose-400 focus:ring-rose-300 @enderror" placeholder="Town, Estate, Street" required>
                    @error('business_location')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <p class="text-base font-semibold text-slate-900">Product & Loan Structure</p>
                    <p class="text-sm text-slate-500">Choose the product and fine-tune amounts, rates and fees.</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Step 2</span>
            </div>
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-slate-700">Loan Product</label>
                    <select name="loan_product_id" class="mt-1 w-full rounded-xl border-slate-200" x-model="selectedProductId" @change="updateFromProduct()">
                        <option value="">Select product</option>
                        @foreach($loanProducts as $product)
                            <option value="{{ $product->id }}" @selected(old('loan_product_id', $application->loan_product_id ?? '') == $product->id)>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-slate-400">Product presets automatically adjust interest rate suggestions.</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Loan Amount (KES)</label>
                    <input type="number" min="1000" step="0.01" name="amount" x-model="amount" @input="calculateTotals()" value="{{ old('amount', $application->amount ?? '') }}" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Duration (Months)</label>
                    <input type="number" min="1" name="duration_months" x-model="duration" @input="calculateTotals()" value="{{ old('duration_months', $application->duration_months ?? 12) }}" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-slate-700">Payment Frequency & Interest</label>
                    </div>
                    <select name="repayment_frequency" class="mt-1 w-full rounded-xl border-slate-200 text-sm" x-model="selectedFrequencyKey" @change="applyFrequency()">
                        @foreach($repaymentFrequencies as $key => $frequency)
                            <option value="{{ $key }}" @selected(old('repayment_frequency', $application->repayment_frequency ?? 'monthly') === $key)>
                                {{ $frequency['label'] }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="repayment_interval_weeks" :value="repaymentIntervalWeeks">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Interest Rate (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="interest_rate" x-model="interestRate" value="{{ old('interest_rate', $application->interest_rate ?? 35) }}" class="mt-1 w-full rounded-xl border-slate-200" readonly>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Registration / Processing Fee (KES)</label>
                    <input type="number" min="0" step="0.01" name="registration_fee" x-model="registrationFee" @input="calculateTotals()" value="{{ old('registration_fee', $application->registration_fee ?? 0) }}" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
            </div>
            <input type="hidden" name="interest_rate_type" value="frequency">
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Officer Assignment</p>
                <p class="mt-1 text-xs text-slate-400">Assign workflow owners for smooth approvals.</p>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="text-xs font-medium uppercase tracking-wide text-slate-500">Loan Officer</label>
                        <select name="loan_officer_id" class="mt-1 w-full rounded-xl border-slate-200">
                            <option value="">Select</option>
                            @foreach($loanOfficers as $officer)
                                <option value="{{ $officer->id }}" @selected(old('loan_officer_id', $application->loan_officer_id ?? '') == $officer->id)>
                                    {{ $officer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium uppercase tracking-wide text-slate-500">Credit Officer</label>
                        <select name="credit_officer_id" class="mt-1 w-full rounded-xl border-slate-200">
                            <option value="">Select</option>
                            @foreach($creditOfficers as $officer)
                                <option value="{{ $officer->id }}" @selected(old('credit_officer_id', $application->credit_officer_id ?? '') == $officer->id)>
                                    {{ $officer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium uppercase tracking-wide text-slate-500">Collection Officer</label>
                        <select name="collection_officer_id" class="mt-1 w-full rounded-xl border-slate-200">
                            <option value="">Select</option>
                            @foreach($collectionOfficers as $officer)
                                <option value="{{ $officer->id }}" @selected(old('collection_officer_id', $application->collection_officer_id ?? '') == $officer->id)>
                                    {{ $officer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium uppercase tracking-wide text-slate-500">Finance Officer</label>
                        <select name="finance_officer_id" class="mt-1 w-full rounded-xl border-slate-200">
                            <option value="">Select</option>
                            @foreach($financeOfficers as $officer)
                                <option value="{{ $officer->id }}" @selected(old('finance_officer_id', $application->finance_officer_id ?? '') == $officer->id)>
                                    {{ $officer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 lg:col-span-2">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Document Uploads</p>
                <p class="mt-1 text-xs text-slate-400">Attach clear scans/photos. All file inputs support multi-select.</p>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <label class="flex flex-col rounded-xl border border-dashed border-emerald-200 bg-emerald-50/40 p-4 text-sm text-slate-600">
                        <span class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Loan Form(s)</span>
                        <span class="text-xs text-emerald-500">PDF, JPG or PNG · up to 5MB each</span>
                        <input type="file" name="loan_form[]" multiple accept=".pdf,image/*" class="mt-3 rounded-lg border border-emerald-200 bg-white px-3 py-2 text-xs text-slate-500 file:mr-3 file:rounded-lg file:border file:border-emerald-200 file:bg-emerald-500/10 file:px-3 file:py-1 file:text-emerald-700">
                    </label>
                    <label class="flex flex-col rounded-xl border border-dashed border-sky-200 bg-sky-50/40 p-4 text-sm text-slate-600">
                        <span class="text-xs font-semibold uppercase tracking-wide text-sky-700">M-PESA Statement(s)</span>
                        <span class="text-xs text-sky-500">PDF, JPG or PNG · up to 5MB each</span>
                        <input type="file" name="mpesa_statement[]" multiple accept=".pdf,image/*" class="mt-3 rounded-lg border border-sky-200 bg-white px-3 py-2 text-xs text-slate-500 file:mr-3 file:rounded-lg file:border file:border-sky-200 file:bg-sky-500/10 file:px-3 file:py-1 file:text-sky-700">
                    </label>
                    <label class="flex flex-col rounded-xl border border-dashed border-amber-200 bg-amber-50/50 p-4 text-sm text-slate-600">
                        <span class="text-xs font-semibold uppercase tracking-wide text-amber-700">Business Photo(s)</span>
                        <span class="text-xs text-amber-500">High-quality JPG/PNG · up to 5MB each</span>
                        <input type="file" name="business_photo[]" multiple accept="image/*" class="mt-3 rounded-lg border border-amber-200 bg-white px-3 py-2 text-xs text-slate-500 file:mr-3 file:rounded-lg file:border file:border-amber-200 file:bg-amber-500/10 file:px-3 file:py-1 file:text-amber-700">
                    </label>
                    <label class="flex flex-col rounded-xl border border-dashed border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                        <span class="text-xs font-semibold uppercase tracking-wide text-slate-700">Supporting Documents</span>
                        <span class="text-xs text-slate-500">Upload any extra statements, IDs or references.</span>
                        <input type="file" name="documents[]" multiple class="mt-3 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-500 file:mr-3 file:rounded-lg file:border file:border-slate-200 file:bg-slate-200/30 file:px-3 file:py-1 file:text-slate-700">
                    </label>
                </div>
                <p class="mt-3 text-xs text-slate-400">Tip: hold CTRL / CMD when selecting to upload several files at once.</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 lg:col-span-2">
                <label class="text-sm font-medium text-slate-700">Loan Purpose & Notes</label>
                <textarea name="purpose" rows="4" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50/70 p-4 text-sm text-slate-700 focus:border-emerald-400 focus:ring-emerald-400" placeholder="Describe how the funds will be used, key milestones, repayment sources...">{{ old('purpose', $application->purpose ?? '') }}</textarea>
                <p class="mt-1 text-xs text-slate-400">Provide as much context as possible for the approval team.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-emerald-50 to-white p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Real-time Totals</p>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Interest Amount</dt>
                        <dd class="font-semibold text-slate-900" x-text="`KES ${Number(totalInterest).toLocaleString()}`"></dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Total Repayment</dt>
                        <dd class="font-semibold text-emerald-600" x-text="`KES ${Number(totalRepayment).toLocaleString()}`"></dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Weekly Payment</dt>
                        <dd class="font-semibold text-slate-900" x-text="`KES ${Number(weeklyPayment).toLocaleString()}`"></dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Per Cycle (every <span x-text="repaymentIntervalWeeks"></span> wk)</dt>
                        <dd class="font-semibold text-indigo-600" x-text="`KES ${Number(cyclePayment).toLocaleString()}`"></dd>
                    </div>
                </dl>
                <p class="mt-4 text-xs text-slate-400">Totals update automatically as you adjust the loan inputs.</p>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <button type="button" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50" @click.prevent="$store.modal?.close()">
            Cancel
        </button>
        <button type="submit" class="rounded-xl bg-emerald-500 px-6 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-400">
            {{ $isEdit ? 'Update Application' : 'Save Application' }}
        </button>
    </div>
</form>

