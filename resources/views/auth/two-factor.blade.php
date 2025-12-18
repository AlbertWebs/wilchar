<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('We\'ve sent a verification code to your email address. Please enter the code below to complete your login.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('two-factor.verify') }}" class="space-y-4">
        @csrf

        <!-- Verification Code -->
        <div>
            <x-input-label for="code" :value="__('Verification Code')" />
            <x-text-input 
                id="code" 
                class="block mt-1 w-full" 
                type="text" 
                name="code" 
                inputmode="numeric"
                pattern="[0-9]{6}"
                maxlength="6"
                autofocus 
                required 
                autocomplete="one-time-code"
                placeholder="000000"
            />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Enter the 6-digit code sent to your email address.
            </p>
        </div>

        <div class="flex items-center justify-between">
            <form method="POST" action="{{ route('two-factor.resend') }}" class="inline">
                @csrf
                <button type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                    {{ __('Resend Code') }}
                </button>
            </form>

            <x-primary-button>
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
            {{ __('Back to login') }}
        </a>
    </div>
</x-guest-layout>

