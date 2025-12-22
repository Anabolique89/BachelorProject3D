<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $customer = $user->customer;

        if (!$customer) {
            return response()->json([
                'message' => 'No customer profile found'
            ], 403);
        }

        if (!$customer->isAdmin()) {
            return response()->json([
                'message' => 'Admin access required'
            ], 403);
        }

        return $next($request);
    }
}
