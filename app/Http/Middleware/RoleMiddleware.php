<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to access this resource.',
                    'required_roles' => $roles,
                    'user_roles' => [],
                    'type' => 'unauthorized'
                ], 403);
            }
            
            abort(403, 'You must be logged in to access this resource.');
        }

        $user = Auth::user();
        $userRoles = $user->roles->pluck('name')->toArray();
        
        // Check if user has any of the required roles
        $hasRequiredRole = false;
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                $hasRequiredRole = true;
                break;
            }
        }

        if (!$hasRequiredRole) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this resource.',
                    'required_roles' => $roles,
                    'user_roles' => $userRoles,
                    'type' => 'forbidden'
                ], 403);
            }
            
            // Store permission info in session for popup display
            session()->flash('permission_error', [
                'message' => 'You do not have permission to access this resource.',
                'required_roles' => $roles,
                'user_roles' => $userRoles,
                'type' => 'forbidden'
            ]);
            
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
