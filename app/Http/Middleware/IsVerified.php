<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return response()->json([
                "status"    => false,
                "message"   => "unauthenticated",
                "data"      => [],
            ], Response::HTTP_UNAUTHORIZED);
        }
        
        $user = auth()->user();
        
        if (!$user->is_verified) {
            return response()->json([
                "status"    => false,
                "message"   => "akun anda belum diverifikasi",
                "data"      => [],
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
