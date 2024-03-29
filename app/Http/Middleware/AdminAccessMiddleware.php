<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in
        if (!auth()->check()) {
            return response()->json([
                'status' => 401,
                'error' => 'Unauthenticated! Login first!!!'
            ], 401);
        }

        // Check if the user is an admin
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'status' => 403,
                'error' => 'Unauthorized!! Only admins are allowed.'
            ], 403);
        }

        return $next($request);
    }
}
