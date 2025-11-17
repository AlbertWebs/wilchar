<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle 403 Forbidden errors with custom popup
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() === 403) {
                // For AJAX/JSON requests, return JSON response
                if ($request->expectsJson() || $request->ajax()) {
                    $permissionError = session('permission_error');
                    
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage() ?: 'You do not have permission to access this resource.',
                        'required_roles' => $permissionError['required_roles'] ?? [],
                        'user_roles' => $permissionError['user_roles'] ?? [],
                        'type' => $permissionError['type'] ?? 'forbidden'
                    ], 403);
                }
                
                // For regular requests, redirect back with error info
                $permissionError = session('permission_error');
                if (!$permissionError) {
                    $permissionError = [
                        'message' => $e->getMessage() ?: 'You do not have permission to access this resource.',
                        'required_roles' => [],
                        'user_roles' => auth()->check() ? auth()->user()->roles->pluck('name')->toArray() : [],
                        'type' => 'forbidden'
                    ];
                }
                
                // Store in session for popup display
                session()->flash('permission_error', $permissionError);
                
                // Redirect back or to dashboard
                return redirect()->back()->with('error', $permissionError['message']);
            }
        });
    })->create();
