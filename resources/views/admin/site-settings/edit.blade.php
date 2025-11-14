@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.admin', ['title' => 'Site Settings'])

@section('header')
    Site Settings
@endsection

@section('content')
    <x-admin.section title="General Settings" description="Update branding, contact info, and metadata.">
        <form action="{{ route('admin.site-settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-slate-700">Company Name</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a5 5 0 115-5v5z"/></svg>
                        </span>
                        <input type="text" name="site_name" value="{{ old('site_name', $defaultSettings['general']['site_name']) }}" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Support Email</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12H8m8 0l-4 4m4-4l-4-4"/></svg>
                        </span>
                        <input type="email" name="site_email" value="{{ old('site_email', $defaultSettings['general']['site_email']) }}" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Support Phone</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2 5l5 1 2 5-3 3a14 14 0 006 6l3-3 5 2 1 5c-9 0-16-7-16-16z"/></svg>
                        </span>
                        <input type="text" name="site_phone" value="{{ old('site_phone', $defaultSettings['general']['site_phone']) }}" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Address</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21l-6-7a7 7 0 1112 0l-6 7z"/></svg>
                        </span>
                        <input type="text" name="site_address" value="{{ old('site_address', $defaultSettings['general']['site_address']) }}" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400">
                    </div>
                </div>
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">Footer Note</label>
                <textarea name="footer_text" rows="3" class="mt-1 w-full rounded-xl border-slate-200 focus:border-emerald-400 focus:ring-emerald-400">{{ old('footer_text', $defaultSettings['general']['footer_text']) }}</textarea>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                @php
                    $placeholders = [
                        'logo' => $defaultSettings['branding']['logo'] ? Storage::url($defaultSettings['branding']['logo']) : 'https://placehold.co/100x100?text=Logo',
                        'logo_dark' => $defaultSettings['branding']['logo_dark'] ? Storage::url($defaultSettings['branding']['logo_dark']) : 'https://placehold.co/100x100?text=Dark',
                        'favicon' => $defaultSettings['branding']['favicon'] ? Storage::url($defaultSettings['branding']['favicon']) : 'https://placehold.co/100x100?text=Icon',
                    ];
                @endphp
                @foreach(['logo' => 'Logo', 'logo_dark' => 'Dark Logo', 'favicon' => 'Favicon'] as $key => $label)
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">{{ $label }}</label>
                        <div class="flex items-center gap-3">
                            <div class="h-24 w-24 overflow-hidden rounded-xl border border-slate-200 bg-white">
                                <img src="{{ $placeholders[$key] }}" alt="{{ $label }}" class="h-full w-full object-cover">
                            </div>
                            <label class="flex w-full cursor-pointer flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50 px-3 py-2 text-center text-xs font-medium text-slate-500 hover:border-emerald-400 hover:text-emerald-500">
                                <svg class="mb-1 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l6-6 4 4 5-5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4h16v16H4z"/></svg>
                                Upload
                                <input type="file" name="{{ $key }}" class="hidden">
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-slate-700">Facebook URL</label>
                    <input type="url" name="facebook_url" value="{{ old('facebook_url', $defaultSettings['social']['facebook_url']) }}" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Twitter URL</label>
                    <input type="url" name="twitter_url" value="{{ old('twitter_url', $defaultSettings['social']['twitter_url']) }}" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Instagram URL</label>
                    <input type="url" name="instagram_url" value="{{ old('instagram_url', $defaultSettings['social']['instagram_url']) }}" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">LinkedIn URL</label>
                    <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $defaultSettings['social']['linkedin_url']) }}" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="text-sm font-medium text-slate-700">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $defaultSettings['seo']['meta_title']) }}" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Meta Description</label>
                    <input type="text" name="meta_description" value="{{ old('meta_description', $defaultSettings['seo']['meta_description']) }}" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $defaultSettings['seo']['meta_keywords']) }}" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                    Save Settings
                </button>
            </div>
        </form>
    </x-admin.section>
@endsection

