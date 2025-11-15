@extends('layouts.admin', ['title' => 'Sandbox Data Purge'])

@section('header')
    Sandbox Data Purge
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Danger Zone" description="Remove all transactional data while keeping user accounts intact.">
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                <p class="font-semibold">Sandbox only</p>
                <p class="mt-2">
                    This will delete loans, applications, approvals, teams, payments, and every record except user accounts and core settings.
                    Use this only to reset your demo data. This action cannot be undone.
                </p>
            </div>

            <div class="mt-5 rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-100 px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Tables to purge ({{ count($tables) }})
                </div>
                <div class="max-h-64 overflow-y-auto px-6 py-4 text-sm text-slate-600">
                    <ul class="space-y-1">
                        @foreach($tables as $table)
                            <li class="flex items-center justify-between border-b border-slate-100/60 py-1 last:border-0">
                                <span>{{ $table }}</span>
                                <span class="text-xs text-slate-400">{{ number_format($approxRecords[$table]) }} rows</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <form method="POST" action="{{ route('sandbox.purge.run') }}" class="mt-6 space-y-4">
                @csrf
                <label class="flex items-start gap-3 text-sm text-slate-700">
                    <input type="checkbox" name="confirmation" value="1" class="mt-1 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                    <span>I understand this will permanently delete all sandbox data except user accounts.</span>
                </label>
                <button type="submit" class="w-full rounded-2xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-400">
                    Purge Sandbox Data
                </button>
            </form>
        </x-admin.section>
    </div>
@endsection

