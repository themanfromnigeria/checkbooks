<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthorAccessMiddleware
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

        // Check if the user is an author or admin
        if (auth()->user()->role !== 'author' && auth()->user()->role !== 'admin') {
            return response()->json([
                'status' => 403,
                'error' => 'Unauthorized!!'
            ], 403);
        }

        return $next($request);
    }
}
