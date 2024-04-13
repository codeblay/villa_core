<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('x-app-key', '') == config('app.key')) return $next($request);

        return response()->json([
            'status'    => false,
            'message'   => "invalid key",
            'data'      => [],
        ], Response::HTTP_UNAUTHORIZED);
    }
}
