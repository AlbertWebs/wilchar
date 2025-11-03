@extends('adminlte::page')

@section('title', 'Site Settings')

@section('content_header')
    <h1 class="text-2xl font-bold">Site Settings</h1>
@stop

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <form action="{{ route('admin.site-settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab">
                    <i class="fas fa-cog mr-2"></i>General
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="branding-tab" data-toggle="tab" href="#branding" role="tab">
                    <i class="fas fa-palette mr-2"></i>Branding
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="social-tab" data-toggle="tab" href="#social" role="tab">
                    <i class="fas fa-share-alt mr-2"></i>Social Media
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="seo-tab" data-toggle="tab" href="#seo" role="tab">
                    <i class="fas fa-search mr-2"></i>SEO
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="settingsTabContent">
            <!-- General Settings Tab -->
            <div class="tab-pane fade show active" id="general" role="tabpanel">
                <div class="bg-white rounded-lg shadow p-6 space-y-4">
                    <h3 class="text-lg font-semibold mb-4">General Settings</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                            <input type="text" name="site_name" value="{{ old('site_name', $defaultSettings['general']['site_name'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('site_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Site Tagline</label>
                            <input type="text" name="site_tagline" value="{{ old('site_tagline', $defaultSettings['general']['site_tagline'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('site_tagline')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                            <input type="email" name="site_email" value="{{ old('site_email', $defaultSettings['general']['site_email'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('site_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                            <input type="text" name="site_phone" value="{{ old('site_phone', $defaultSettings['general']['site_phone'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('site_phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Site Address</label>
                            <textarea name="site_address" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('site_address', $defaultSettings['general']['site_address'] ?? '') }}</textarea>
                            @error('site_address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Footer Text</label>
                            <textarea name="footer_text" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('footer_text', $defaultSettings['general']['footer_text'] ?? '') }}</textarea>
                            @error('footer_text')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Branding Tab -->
            <div class="tab-pane fade" id="branding" role="tabpanel">
                <div class="bg-white rounded-lg shadow p-6 space-y-4">
                    <h3 class="text-lg font-semibold mb-4">Branding & Images</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Logo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Main Logo</label>
                            @if(isset($defaultSettings['branding']['logo']) && $defaultSettings['branding']['logo'])
                            <div class="mb-3">
                                <img src="{{ Storage::url($defaultSettings['branding']['logo']) }}" alt="Current Logo" 
                                     class="max-w-xs h-20 object-contain border border-gray-300 rounded p-2">
                                <p class="text-xs text-gray-500 mt-1">Current Logo</p>
                            </div>
                            @endif
                            <input type="file" name="logo" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Recommended: PNG or SVG, max 2MB</p>
                            @error('logo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Dark Mode Logo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dark Mode Logo</label>
                            @if(isset($defaultSettings['branding']['logo_dark']) && $defaultSettings['branding']['logo_dark'])
                            <div class="mb-3">
                                <img src="{{ Storage::url($defaultSettings['branding']['logo_dark']) }}" alt="Current Dark Logo" 
                                     class="max-w-xs h-20 object-contain border border-gray-300 rounded p-2 bg-gray-800">
                                <p class="text-xs text-gray-500 mt-1">Current Dark Logo</p>
                            </div>
                            @endif
                            <input type="file" name="logo_dark" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Logo for dark theme (optional)</p>
                            @error('logo_dark')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Favicon -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                            @if(isset($defaultSettings['branding']['favicon']) && $defaultSettings['branding']['favicon'])
                            <div class="mb-3">
                                <img src="{{ Storage::url($defaultSettings['branding']['favicon']) }}" alt="Current Favicon" 
                                     class="w-16 h-16 object-contain border border-gray-300 rounded p-2">
                                <p class="text-xs text-gray-500 mt-1">Current Favicon</p>
                            </div>
                            @endif
                            <input type="file" name="favicon" accept="image/x-icon,image/png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Recommended: .ico or .png, 32x32 or 16x16 pixels, max 512KB</p>
                            @error('favicon')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media Tab -->
            <div class="tab-pane fade" id="social" role="tabpanel">
                <div class="bg-white rounded-lg shadow p-6 space-y-4">
                    <h3 class="text-lg font-semibold mb-4">Social Media Links</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-facebook text-blue-600 mr-2"></i>Facebook URL
                            </label>
                            <input type="url" name="facebook_url" value="{{ old('facebook_url', $defaultSettings['social']['facebook_url'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="https://facebook.com/yourpage">
                            @error('facebook_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-twitter text-blue-400 mr-2"></i>Twitter URL
                            </label>
                            <input type="url" name="twitter_url" value="{{ old('twitter_url', $defaultSettings['social']['twitter_url'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="https://twitter.com/yourhandle">
                            @error('twitter_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-instagram text-pink-500 mr-2"></i>Instagram URL
                            </label>
                            <input type="url" name="instagram_url" value="{{ old('instagram_url', $defaultSettings['social']['instagram_url'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="https://instagram.com/yourhandle">
                            @error('instagram_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-linkedin text-blue-700 mr-2"></i>LinkedIn URL
                            </label>
                            <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $defaultSettings['social']['linkedin_url'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="https://linkedin.com/company/yourcompany">
                            @error('linkedin_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Tab -->
            <div class="tab-pane fade" id="seo" role="tabpanel">
                <div class="bg-white rounded-lg shadow p-6 space-y-4">
                    <h3 class="text-lg font-semibold mb-4">SEO Settings</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title', $defaultSettings['seo']['meta_title'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="Site meta title (max 60 characters)">
                            <p class="text-xs text-gray-500 mt-1">Recommended: 50-60 characters</p>
                            @error('meta_title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                            <textarea name="meta_description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                      placeholder="Site meta description">{{ old('meta_description', $defaultSettings['seo']['meta_description'] ?? '') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Recommended: 150-160 characters</p>
                            @error('meta_description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $defaultSettings['seo']['meta_keywords'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="keyword1, keyword2, keyword3">
                            <p class="text-xs text-gray-500 mt-1">Comma-separated keywords</p>
                            @error('meta_keywords')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <div class="flex justify-end gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium inline-flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </div>
        </div>
    </form>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

@section('js')
<script>
    // Tab switching functionality is handled by AdminLTE/Bootstrap
    // Preview image when file is selected
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create or update preview
                    let preview = input.parentElement.querySelector('.preview-image');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.className = 'preview-image max-w-xs h-20 object-contain border border-gray-300 rounded p-2 mt-2';
                        input.parentElement.appendChild(preview);
                    }
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@stop

