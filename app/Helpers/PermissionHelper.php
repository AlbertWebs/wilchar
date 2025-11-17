<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Abort if user doesn't have the required role, with detailed error message
     */
    public static function requireRole($role, $message = null)
    {
        $user = Auth::user();
        
        if (!$user) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to access this resource.',
                    'required_roles' => is_array($role) ? $role : [$role],
                    'user_roles' => [],
                    'type' => 'unauthorized'
                ], 403);
            }
            
            abort(403, 'You must be logged in to access this resource.');
        }
        
        $requiredRoles = is_array($role) ? $role : [$role];
        $userRoles = $user->roles->pluck('name')->toArray();
        
        $hasRole = false;
        foreach ($requiredRoles as $r) {
            if ($user->hasRole($r)) {
                $hasRole = true;
                break;
            }
        }
        
        if (!$hasRole) {
            $errorMessage = $message ?: 'You do not have permission to access this resource.';
            
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'required_roles' => $requiredRoles,
                    'user_roles' => $userRoles,
                    'type' => 'forbidden'
                ], 403);
            }
            
            session()->flash('permission_error', [
                'message' => $errorMessage,
                'required_roles' => $requiredRoles,
                'user_roles' => $userRoles,
                'type' => 'forbidden'
            ]);
            
            abort(403, $errorMessage);
        }
        
        return true;
    }
    
    /**
     * Abort if user doesn't have the required permission, with detailed error message
     */
    public static function requirePermission($permission, $message = null)
    {
        $user = Auth::user();
        
        if (!$user) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to access this resource.',
                    'required_permissions' => is_array($permission) ? $permission : [$permission],
                    'user_permissions' => [],
                    'type' => 'unauthorized'
                ], 403);
            }
            
            abort(403, 'You must be logged in to access this resource.');
        }
        
        $requiredPermissions = is_array($permission) ? $permission : [$permission];
        $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();
        
        $hasPermission = false;
        foreach ($requiredPermissions as $p) {
            if ($user->can($p)) {
                $hasPermission = true;
                break;
            }
        }
        
        if (!$hasPermission) {
            $errorMessage = $message ?: 'You do not have permission to perform this action.';
            
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'required_permissions' => $requiredPermissions,
                    'user_permissions' => $userPermissions,
                    'type' => 'forbidden'
                ], 403);
            }
            
            session()->flash('permission_error', [
                'message' => $errorMessage,
                'required_permissions' => $requiredPermissions,
                'user_permissions' => $userPermissions,
                'type' => 'forbidden'
            ]);
            
            abort(403, $errorMessage);
        }
        
        return true;
    }
}

