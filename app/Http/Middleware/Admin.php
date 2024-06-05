<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Admin extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('admin.login');
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if (!auth()->check()) return redirect()->route('admin.login');
        
        $is_admin = auth()->user()->is_admin ?? false;
        
        return $is_admin ? $next($request) : abort(Response::HTTP_FORBIDDEN);
    }
}
