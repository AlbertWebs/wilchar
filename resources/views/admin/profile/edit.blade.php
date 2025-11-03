@extends('adminlte::page')

@section('title', 'Profile')

@section('content_header')
    <h1 class="text-2xl font-bold">My Profile</h1>
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

    <div class="space-y-6">
        <!-- Profile Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-user text-blue-500"></i> Profile Information
            </h3>
            
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2 flex items-center gap-6">
                        <!-- Avatar Display -->
                        <div>
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" 
                                     class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                            @else
                                <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center border-4 border-gray-200">
                                    <i class="fas fa-user text-gray-400 text-3xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                            <input type="file" name="avatar" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Recommended: Square image, max 2MB</p>
                            @error('avatar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        @if($user->email_verified_at)
                            <p class="text-xs text-green-600 mt-1">
                                <i class="fas fa-check-circle"></i> Email verified
                            </p>
                        @else
                            <p class="text-xs text-yellow-600 mt-1">
                                <i class="fas fa-exclamation-circle"></i> Email not verified
                            </p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="+254 700 000 000">
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <input type="text" value="{{ $user->roles->pluck('name')->join(', ') ?: 'No role assigned' }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" disabled>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium inline-flex items-center gap-2">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-lock text-red-500"></i> Change Password
            </h3>
            
            <form action="{{ route('admin.profile.password.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password <span class="text-red-500">*</span></label>
                        <input type="password" name="current_password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium inline-flex items-center gap-2">
                        <i class="fas fa-key"></i> Update Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Account Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-red-600">
                <i class="fas fa-exclamation-triangle"></i> Danger Zone
            </h3>
            
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-sm text-red-800 mb-4">
                    Once you delete your account, there is no going back. Please be certain.
                </p>
                
                <form action="{{ route('admin.profile.destroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-red-700 mb-1">Enter Password to Confirm <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required
                               class="w-full px-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500"
                               placeholder="Enter your password">
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium inline-flex items-center gap-2">
                        <i class="fas fa-trash"></i> Delete My Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

@section('js')
<script>
    // Preview avatar when file is selected
    document.querySelector('input[name="avatar"]')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.querySelector('img[alt="Avatar"]') || document.querySelector('.fa-user').closest('div');
                if (img && img.tagName === 'IMG') {
                    img.src = e.target.result;
                } else {
                    // Replace placeholder div with image
                    const placeholder = document.querySelector('.fa-user')?.closest('div');
                    if (placeholder) {
                        const newImg = document.createElement('img');
                        newImg.src = e.target.result;
                        newImg.alt = 'Avatar';
                        newImg.className = 'w-24 h-24 rounded-full object-cover border-4 border-gray-200';
                        placeholder.replaceWith(newImg);
                    }
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@stop

