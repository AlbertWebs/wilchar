@extends('layouts.admin', ['title' => 'Create Client'])

@section('header')
    Create Client
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Client Profile" description="Capture the borrower’s personal details and business background.">
            <form action="{{ route('clients.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="mt-1 w-full rounded-xl border-slate-200" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="mt-1 w-full rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="mt-1 w-full rounded-xl border-slate-200" required>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">National ID / Passport</label>
                        <input type="text" name="id_number" value="{{ old('id_number') }}" class="mt-1 w-full rounded-xl border-slate-200" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="2547XXXXXXXX" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email (optional)</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-xl border-slate-200">
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="mt-1 w-full rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Gender</label>
                        <select name="gender" class="mt-1 w-full rounded-xl border-slate-200">
                            <option value="">Select</option>
                            <option value="male" @selected(old('gender') === 'male')>Male</option>
                            <option value="female" @selected(old('gender') === 'female')>Female</option>
                            <option value="other" @selected(old('gender') === 'other')>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Nationality</label>
                        <input type="text" name="nationality" value="{{ old('nationality') }}" class="mt-1 w-full rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                        <select name="status" class="mt-1 w-full rounded-xl border-slate-200">
                            <option value="active" @selected(old('status') === 'active')>Active</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                            <option value="blacklisted" @selected(old('status') === 'blacklisted')>Blacklisted</option>
                        </select>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Business Details</p>
                    <div class="mt-4 grid gap-4 md:grid-cols-3">
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Business Name</label>
                            <input type="text" name="business_name" value="{{ old('business_name') }}" class="mt-1 w-full rounded-xl border-slate-200" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Business Type</label>
                            <input type="text" name="business_type" value="{{ old('business_type') }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="Retail, Farming..." required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Business Location</label>
                            <input type="text" name="location" value="{{ old('location') }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="Town · Street · Landmark" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Address (optional)</label>
                            <input type="text" name="address" value="{{ old('address') }}" class="mt-1 w-full rounded-xl border-slate-200">
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Employment & Contacts</p>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Occupation</label>
                            <input type="text" name="occupation" value="{{ old('occupation') }}" class="mt-1 w-full rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Employer</label>
                            <input type="text" name="employer" value="{{ old('employer') }}" class="mt-1 w-full rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">M-PESA Phone</label>
                            <input type="text" name="mpesa_phone" value="{{ old('mpesa_phone') }}" class="mt-1 w-full rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Alternate Phone</label>
                            <input type="text" name="alternate_phone" value="{{ old('alternate_phone') }}" class="mt-1 w-full rounded-xl border-slate-200">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('clients.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        Save Client
                    </button>
                </div>
            </form>
        </x-admin.section>
    </div>
@endsection

