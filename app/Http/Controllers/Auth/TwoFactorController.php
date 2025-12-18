<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\TwoFactorCodeNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    /**
     * Show the two-factor authentication form.
     */
    public function show(): View
    {
        $userId = session('login.id');
        
        if (!$userId) {
            return redirect()->route('login');
        }

        return view('auth.two-factor');
    }

    /**
     * Verify the two-factor authentication code.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $userId = session('login.id');
        
        if (!$userId) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please login again.');
        }

        $user = \App\Models\User::find($userId);

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'User not found.');
        }

        // Check rate limiting
        $key = 'two-factor:' . $user->id . ':' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'code' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // Verify code
        if (!$user->two_factor_code || 
            $user->two_factor_code !== $request->code ||
            !$user->two_factor_expires_at ||
            $user->two_factor_expires_at->isPast()) {
            
            RateLimiter::hit($key);
            
            throw ValidationException::withMessages([
                'code' => 'The verification code is invalid or has expired.',
            ]);
        }

        // Clear the code
        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->save();

        // Clear rate limiter
        RateLimiter::clear($key);

        // Clear login session
        session()->forget('login.id');

        // Log the user in
        Auth::login($user, session('login.remember', false));

        // Regenerate session
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Resend the two-factor authentication code.
     */
    public function resend(Request $request): RedirectResponse
    {
        $userId = session('login.id');
        
        if (!$userId) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please login again.');
        }

        $user = \App\Models\User::find($userId);

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'User not found.');
        }

        // Check rate limiting for resend
        $key = 'two-factor-resend:' . $user->id . ':' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]));
        }

        // Generate new code
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->two_factor_code = $code;
        $user->two_factor_expires_at = now()->addMinutes(10);
        $user->save();

        // Send notification
        $user->notify(new TwoFactorCodeNotification($code));

        RateLimiter::hit($key);

        return back()->with('status', 'A new verification code has been sent to your email.');
    }
}
