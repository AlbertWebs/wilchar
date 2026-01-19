@extends('layouts.admin', ['title' => 'Legal Pages'])

@section('header')
    Legal Pages
@endsection

@section('content')
    <x-admin.section title="Legal & Compliance Pages" description="Manage Terms & Conditions, Privacy Policy, Copyright Statement, and CBK Disclaimer.">
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            @foreach($pages as $key => $pageData)
                @php
                    $type = $pageData['type'];
                    $page = $pageData['page'];
                    $exists = $pageData['exists'];
                @endphp
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="mb-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100">
                                @if($key === 'terms')
                                    <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                @elseif($key === 'privacy')
                                    <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                @elseif($key === 'copyright')
                                    <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                @else
                                    <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">{{ $type['title'] }}</h3>
                                <p class="text-xs text-slate-500">{{ $exists ? 'Published' : 'Not Created' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        @if($exists)
                            <a href="{{ route('page.show', $type['slug']) }}" target="_blank" class="flex-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-center text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                View
                            </a>
                        @endif
                        <a href="{{ route('admin.legal-pages.edit', $key) }}" class="flex-1 rounded-lg bg-emerald-500 px-3 py-2 text-center text-xs font-semibold text-white hover:bg-emerald-600">
                            {{ $exists ? 'Edit' : 'Create' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </x-admin.section>
@endsection
