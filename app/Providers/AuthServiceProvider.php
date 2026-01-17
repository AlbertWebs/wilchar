<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Policy mappings can be added here
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Register gates for all permissions to allow Admin to bypass checks
        Gate::before(function ($user, $ability) {
            // Admin role has access to everything
            // Check for both 'Admin' and 'Super Admin' role names
            if ($user && ($user->hasRole('Admin') || $user->hasRole('Super Admin'))) {
                return true;
            }
        });
    }
}
