<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        // Normalize permissions: split pipe-separated permissions and flatten the array
        // Routes like 'permission:clients.view|clients.create' pass as single string argument
        $normalizedPermissions = [];
        foreach ($permissions as $permission) {
            if (is_string($permission) && str_contains($permission, '|')) {
                // Split pipe-separated permissions and trim whitespace
                $splitPermissions = array_map('trim', explode('|', $permission));
                $normalizedPermissions = array_merge($normalizedPermissions, $splitPermissions);
            } else {
                $normalizedPermissions[] = is_string($permission) ? trim($permission) : $permission;
            }
        }
        $permissions = array_unique($normalizedPermissions); // Remove duplicates
        
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to access this resource.',
                    'required_permissions' => $permissions,
                    'user_permissions' => [],
                    'type' => 'unauthorized'
                ], 403);
            }
            
            abort(403, 'You must be logged in to access this resource.');
        }

        $user = Auth::user();
        $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();
        
        // Check if user has any of the required permissions
        // Admin role always has all permissions
        $hasRequiredPermission = false;
        
        // Check for both 'Admin' and 'Super Admin' role names
        if ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {
            // Admin has all permissions
            $hasRequiredPermission = true;
        } else {
            // Check if user has any of the required permissions
            foreach ($permissions as $permission) {
                if ($user->can($permission)) {
                    $hasRequiredPermission = true;
                    break;
                }
            }
        }

        if (!$hasRequiredPermission) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this resource.',
                    'required_permissions' => $permissions,
                    'user_permissions' => $userPermissions,
                    'type' => 'forbidden'
                ], 403);
            }
            
            // Store permission info in session for popup display
            session()->flash('permission_error', [
                'message' => 'You do not have permission to access this resource.',
                'required_permissions' => $permissions,
                'user_permissions' => $userPermissions,
                'type' => 'forbidden'
            ]);
            
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
