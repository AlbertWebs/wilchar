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
                    <label class="text-sm font-medium text-slate-700">Site Tagline</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        </span>
                        <input type="text" name="site_tagline" value="{{ old('site_tagline', $defaultSettings['general']['site_tagline']) }}" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400" placeholder="e.g. Your Trusted Financial Partner">
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
                <div>
                    <label class="text-sm font-medium text-slate-700">Alternate Address</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <input type="text" name="site_address_alt" value="{{ old('site_address_alt', $defaultSettings['general']['site_address_alt']) }}" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400" placeholder="Optional alternate address">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Location</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <input type="text" name="site_location" value="{{ old('site_location', $defaultSettings['general']['site_location']) }}" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400" placeholder="e.g. Nairobi, Kenya">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Alternate Phone</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2 5l5 1 2 5-3 3a14 14 0 006 6l3-3 5 2 1 5c-9 0-16-7-16-16z"/></svg>
                        </span>
                        <input type="text" name="site_phone_alt" value="{{ old('site_phone_alt', $defaultSettings['general']['site_phone_alt']) }}" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400" placeholder="e.g. 0793793362">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">M-Pesa Paybill Number</label>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </span>
                        <input type="text" name="paybill_number" value="{{ old('paybill_number', $defaultSettings['general']['paybill_number']) }}" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400" placeholder="e.g. 4189755">
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-slate-700">Footer Description</label>
                    <textarea name="footer_description" rows="3" class="mt-1 w-full rounded-xl border-slate-200 focus:border-emerald-400 focus:ring-emerald-400" placeholder="Brief description shown in footer">{{ old('footer_description', $defaultSettings['general']['footer_description']) }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Footer Copyright Text</label>
                    <textarea name="footer_text" rows="3" class="mt-1 w-full rounded-xl border-slate-200 focus:border-emerald-400 focus:ring-emerald-400" placeholder="Copyright text">{{ old('footer_text', $defaultSettings['general']['footer_text']) }}</textarea>
                </div>
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">Footer Powered By</label>
                <div class="relative mt-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </span>
                    <input type="text" name="footer_powered_by" value="{{ old('footer_powered_by', $defaultSettings['general']['footer_powered_by']) }}" class="w-full rounded-xl border-slate-200 pl-9 pr-3 py-2 focus:border-emerald-400 focus:ring-emerald-400" placeholder="e.g. Powered by Your Company Name">
                    <p class="mt-1 text-xs text-slate-500">This text appears after the copyright notice in the footer</p>
                </div>
            </div>

            <x-admin.section title="Branding & Logo" description="Upload your company logo and favicon. Recommended: Logo (PNG, transparent background, max 2MB), Favicon (ICO or PNG, 32x32px, max 512KB).">
                <div class="grid gap-6 md:grid-cols-3">
                    @php
                        $placeholders = [
                            'logo' => $defaultSettings['branding']['logo'] ? asset('storage/' . $defaultSettings['branding']['logo']) : asset('main/assets/images/logo.png'),
                            'logo_dark' => $defaultSettings['branding']['logo_dark'] ? asset('storage/' . $defaultSettings['branding']['logo_dark']) : asset('main/assets/images/logo.png'),
                            'favicon' => $defaultSettings['branding']['favicon'] ? asset('storage/' . $defaultSettings['branding']['favicon']) : asset('main/assets/images/favicon.png'),
                        ];
                    @endphp
                    @foreach(['logo' => 'Main Logo', 'logo_dark' => 'Dark Mode Logo (Optional)', 'favicon' => 'Favicon'] as $key => $label)
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">{{ $label }}</label>
                            <div class="flex flex-col gap-3">
                                <div class="h-32 w-full overflow-hidden rounded-xl border border-slate-200 bg-white p-2 flex items-center justify-center">
                                    <img src="{{ $placeholders[$key] }}" alt="{{ $label }}" class="max-h-full max-w-full object-contain" id="preview-{{ $key }}" onerror="this.src='{{ asset('main/assets/images/logo.png') }}'">
                                </div>
                                <label class="flex w-full cursor-pointer flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50 px-3 py-3 text-center text-xs font-medium text-slate-500 hover:border-emerald-400 hover:bg-emerald-50 hover:text-emerald-500 transition">
                                    <svg class="mb-1 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l6-6 4 4 5-5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4h16v16H4z"/></svg>
                                    <span>Click to Upload</span>
                                    <input type="file" name="{{ $key }}" class="hidden" accept="image/*" onchange="previewImage(this, 'preview-{{ $key }}')">
                                </label>
                                @if($defaultSettings['branding'][$key])
                                    <p class="text-xs text-slate-500">Current: {{ basename($defaultSettings['branding'][$key]) }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-admin.section>
            
            <script>
                function previewImage(input, previewId) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById(previewId).src = e.target.result;
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }
            </script>

            <x-admin.section title="Social Media Links" description="Add your social media profiles to display in the footer.">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-slate-700">Facebook URL</label>
                        <input type="url" name="facebook_url" value="{{ old('facebook_url', $defaultSettings['social']['facebook_url']) }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="https://facebook.com/yourpage">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">Twitter URL</label>
                        <input type="url" name="twitter_url" value="{{ old('twitter_url', $defaultSettings['social']['twitter_url']) }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="https://twitter.com/yourhandle">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">Instagram URL</label>
                        <input type="url" name="instagram_url" value="{{ old('instagram_url', $defaultSettings['social']['instagram_url']) }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="https://instagram.com/yourhandle">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">LinkedIn URL</label>
                        <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $defaultSettings['social']['linkedin_url']) }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="https://linkedin.com/company/yourcompany">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">YouTube URL</label>
                        <input type="url" name="youtube_url" value="{{ old('youtube_url', $defaultSettings['social']['youtube_url']) }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="https://youtube.com/@yourchannel">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $defaultSettings['social']['whatsapp_number']) }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="e.g. +254712345678">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">Telegram URL</label>
                        <input type="url" name="telegram_url" value="{{ old('telegram_url', $defaultSettings['social']['telegram_url']) }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="https://t.me/yourchannel">
                    </div>
                </div>
            </x-admin.section>

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

