@extends('layouts.admin', ['title' => 'Teams'])

@section('header')
    Team Management
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section
            title="Create Team"
            description="Group loan and collection officers into teams to streamline assignment."
        >
            <form
                x-ajax="{
                    successMessage: { title: 'Team Created', text: 'Team saved successfully.' },
                    onSuccess() { window.location.reload(); }
                }"
                class="grid gap-4 md:grid-cols-3"
                action="{{ route('teams.store') }}"
                method="POST"
            >
                @csrf
                <div class="md:col-span-1">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Team Name</label>
                    <input type="text" name="name" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Description</label>
                    <input type="text" name="description" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div class="md:col-span-3 flex justify-end">
                    <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                        Create Team
                    </button>
                </div>
            </form>
        </x-admin.section>

        <x-admin.section title="Teams" description="Assign members to teams and manage roles.">
            <div class="space-y-4">
                @foreach($teams as $team)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">{{ $team->name }}</h3>
                                <p class="text-xs text-slate-500">{{ $team->description ?? 'No description provided.' }}</p>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <span class="rounded-full bg-emerald-100 px-3 py-1 font-semibold text-emerald-600">
                                    {{ $team->loan_officers_count }} Loan Officers
                                </span>
                                <span class="rounded-full bg-sky-100 px-3 py-1 font-semibold text-sky-600">
                                    {{ $team->collection_officers_count }} Collection Officers
                                </span>
                                <span class="rounded-full bg-amber-100 px-3 py-1 font-semibold text-amber-600">
                                    {{ $team->finance_officers_count }} Finance
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-3 xl:grid-cols-3">
                            @foreach($team->members as $member)
                                <div class="flex items-center justify-between rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $member->name }}</p>
                                        <p class="text-xs text-slate-500">{{ ucfirst(str_replace('_', ' ', $member->pivot->role)) }}</p>
                                    </div>
                                    <form
                                        method="POST"
                                        action="{{ route('teams.members.remove', [$team, $member]) }}"
                                        x-data
                                        @submit.prevent="Admin.confirmAction({ title: 'Remove Member?', text: 'This user will be unassigned from the team.', icon: 'warning', confirmButtonText: 'Remove' }).then(confirmed => { if (confirmed) $el.submit(); })"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-rose-500 hover:text-rose-600">Remove</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        <form
                            class="mt-4 grid gap-3 md:grid-cols-3"
                            action="{{ route('teams.members.assign', $team) }}"
                            method="POST"
                            x-ajax="{ successMessage: { title: 'Member Added' }, onSuccess() { window.location.reload(); } }"
                        >
                            @csrf
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">User</label>
                                <select name="user_id" class="mt-1 w-full rounded-xl border-slate-200" required>
                                    <option value="">Select user</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} · {{ $user->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Role</label>
                                <select name="role" class="mt-1 w-full rounded-xl border-slate-200" required>
                                    <option value="loan_officer">Loan Officer</option>
                                    <option value="collection_officer">Collection Officer</option>
                                    <option value="finance">Finance</option>
                                    <option value="marketer">Marketer</option>
                                    <option value="recovery_officer">Recovery Officer</option>
                                </select>
                            </div>
                            <div class="flex items-end justify-end">
                                <button type="submit" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                                    Assign
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        </x-admin.section>
    </div>
@endsection

