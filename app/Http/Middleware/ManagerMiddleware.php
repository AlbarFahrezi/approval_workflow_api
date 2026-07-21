<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 401);
        }

        // Hanya Manager yang boleh mengakses
        if ($request->user()->role !== 'manager') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya Manager yang dapat melakukan aksi ini.'
            ], 403);
        }

        return $next($request);
    }
}