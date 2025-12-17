<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        // Check if two-factor columns exist before using them
        if (Schema::hasColumn('users', 'two_factor_code')) {
            // Generate two-factor code
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            $user->two_factor_code = $code;
            $user->two_factor_expires_at = now()->addMinutes(10);
            $user->save();

            // Send two-factor code via email
            $user->notify(new \App\Notifications\TwoFactorCodeNotification($code));

            // Store user ID in session for verification
            session(['login.id' => $user->id]);
            session(['login.remember' => $request->boolean('remember')]);

            // Logout the user temporarily until 2FA is verified
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('two-factor.show')
                ->with('status', 'A verification code has been sent to your email address.');
        } else {
            // Two-factor columns don't exist, proceed with normal login
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
