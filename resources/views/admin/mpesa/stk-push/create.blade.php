@extends('layouts.admin', ['title' => 'New STK Push'])

@section('header')
    New STK Push
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section
            title="Initiate STK Push"
            description="Send a Lipa na M-Pesa Online prompt to a client. The form submits over AJAX — you will see success or error without a full page reload."
        >
            <form
                id="stk-push-form"
                action="{{ route('mpesa.stk-push.store') }}"
                method="POST"
                class="max-w-xl space-y-5"
                x-data="stkPushCreate({
                    loans: @js($loansPayload),
                    initialCustom: @json(old('transaction_desc') === '__custom__'),
                })"
                @submit.prevent="submitStk"
            >
                @csrf

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                            Link to loan (optional)
                        </label>
                        <select
                            name="loan_id"
                            x-model="loanId"
                            @change="onLoanChange()"
                            class="mt-1 w-full rounded-xl border-slate-200 bg-white text-sm"
                        >
                            <option value="">— None —</option>
                            @foreach ($loans as $loan)
                                <option value="{{ $loan->id }}">
                                    {{ $loan->application?->application_number ?? 'Loan #' . $loan->id }}
                                    · {{ $loan->client?->full_name ?? '—' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-slate-500">
                            Choosing a loan fills phone, suggested amount, and account reference when available.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                            Phone number (MSISDN)
                        </label>
                        <input
                            type="text"
                            name="phone_number"
                            x-ref="phone"
                            value="{{ old('phone_number') }}"
                            placeholder="2547xxxxxxxx"
                            autocomplete="tel"
                            class="mt-1 w-full rounded-xl border-slate-200 text-sm"
                            required
                        >
                        @error('phone_number')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                            Quick amount (KES)
                        </label>
                        <select
                            x-model="amountPreset"
                            @change="applyAmountPreset()"
                            class="mt-1 w-full rounded-xl border-slate-200 bg-white text-sm"
                        >
                            <option value="">Type manually below</option>
                            <option value="outstanding">Outstanding balance (when loan selected)</option>
                            <option value="500">500</option>
                            <option value="1000">1,000</option>
                            <option value="2000">2,000</option>
                            <option value="5000">5,000</option>
                            <option value="10000">10,000</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                            Amount (KES)
                        </label>
                        <input
                            type="number"
                            step="0.01"
                            min="1"
                            name="amount"
                            x-ref="amount"
                            value="{{ old('amount') }}"
                            class="mt-1 w-full rounded-xl border-slate-200 text-sm"
                            required
                        >
                        @error('amount')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Account reference
                    </label>
                    <input
                        type="text"
                        name="account_reference"
                        x-ref="accountRef"
                        value="{{ old('account_reference') }}"
                        placeholder="Shown on the M-Pesa prompt"
                        class="mt-1 w-full rounded-xl border-slate-200 text-sm"
                    >
                    @error('account_reference')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-slate-500">
                        If empty, the system generates a reference automatically.
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Transaction description
                    </label>
                    <select
                        name="transaction_desc"
                        x-ref="descSelect"
                        @change="showCustomDesc = ($event.target.value === '__custom__')"
                        class="mt-1 w-full rounded-xl border-slate-200 bg-white text-sm"
                        required
                    >
                        @foreach ($transactionDescriptions as $d)
                            <option value="{{ $d }}" @selected(old('transaction_desc', $transactionDescriptions[0] ?? '') === $d)>{{ $d }}</option>
                        @endforeach
                        <option value="__custom__" @selected(old('transaction_desc') === '__custom__')>Other (custom)…</option>
                    </select>
                    <input
                        type="text"
                        name="transaction_desc_custom"
                        x-ref="descCustom"
                        x-show="showCustomDesc"
                        x-cloak
                        value="{{ old('transaction_desc_custom') }}"
                        placeholder="Describe this payment"
                        class="mt-2 w-full rounded-xl border-slate-200 text-sm"
                        :disabled="!showCustomDesc"
                        :required="showCustomDesc"
                    >
                    @error('transaction_desc')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                    @error('transaction_desc_custom')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="stk-form-error" class="hidden rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800"></div>

                <div class="flex flex-wrap items-center gap-3 pt-3">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600 disabled:opacity-60"
                        :disabled="submitting"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="submitting ? 'Sending…' : 'Send STK Push'"></span>
                    </button>

                    <a href="{{ route('mpesa.stk-push.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700">
                        Cancel
                    </a>
                </div>
            </form>
        </x-admin.section>
    </div>

    @push('head')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('stkPushCreate', (config) => ({
                    loans: config.loans || [],
                    loanId: '',
                    amountPreset: '',
                    showCustomDesc: !!config.initialCustom,
                    submitting: false,
                    init() {
                        const sel = this.$refs.descSelect;
                        if (sel && sel.value === '__custom__') {
                            this.showCustomDesc = true;
                        }
                    },
                    selectedLoan() {
                        if (!this.loanId) {
                            return null;
                        }
                        return this.loans.find((l) => String(l.id) === String(this.loanId)) || null;
                    },
                    onLoanChange() {
                        const loan = this.selectedLoan();
                        if (!loan) {
                            return;
                        }
                        if (loan.phone) {
                            this.$refs.phone.value = loan.phone;
                        }
                        this.$refs.amount.value = loan.outstanding;
                        this.$refs.accountRef.value = loan.application_number;
                        this.amountPreset = 'outstanding';
                    },
                    applyAmountPreset() {
                        const loan = this.selectedLoan();
                        if (this.amountPreset === 'outstanding' && loan) {
                            this.$refs.amount.value = loan.outstanding;
                        } else if (this.amountPreset && this.amountPreset !== 'outstanding') {
                            this.$refs.amount.value = this.amountPreset;
                        }
                    },
                    async submitStk() {
                        const form = this.$el;
                        const errEl = document.getElementById('stk-form-error');
                        errEl.classList.add('hidden');
                        errEl.textContent = '';

                        if (this.$refs.descSelect.value === '__custom__' && !this.$refs.descCustom.value.trim()) {
                            errEl.textContent = 'Please enter a custom description.';
                            errEl.classList.remove('hidden');
                            return;
                        }

                        this.submitting = true;
                        const fd = new FormData(form);
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                        try {
                            const response = await window.axios.post(form.action, fd, {
                                headers: {
                                    Accept: 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                            });

                            const data = response.data;
                            if (data.success && data.redirect) {
                                await window.Swal.fire({
                                    icon: 'success',
                                    title: 'STK sent',
                                    text: data.message || 'Customer should receive the M-Pesa prompt.',
                                    timer: 2800,
                                    showConfirmButton: true,
                                });
                                window.location.href = data.redirect;
                                return;
                            }

                            errEl.textContent = data.message || 'Could not initiate STK Push.';
                            errEl.classList.remove('hidden');
                            await window.Swal.fire({
                                icon: 'error',
                                title: 'STK failed',
                                text: data.message || 'Try again or check M-Pesa configuration.',
                            });
                        } catch (e) {
                            const res = e.response;
                            if (res?.status === 422 && res.data?.errors) {
                                const first = Object.values(res.data.errors).flat()[0] || res.data.message;
                                errEl.textContent = first;
                                errEl.classList.remove('hidden');
                                await window.Swal.fire({ icon: 'error', title: 'Check form', text: first });
                            } else {
                                const msg = res?.data?.message || e.message || 'Request failed.';
                                errEl.textContent = msg;
                                errEl.classList.remove('hidden');
                                await window.Swal.fire({ icon: 'error', title: 'Error', text: msg });
                            }
                        } finally {
                            this.submitting = false;
                        }
                    },
                }));
            });
        </script>
    @endpush
@endsection
