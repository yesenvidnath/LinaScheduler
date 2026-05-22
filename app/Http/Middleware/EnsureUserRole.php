<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $designation = optional($user->userDesignation)->Designation;

        if (!$designation || !Str::contains(Str::lower($designation), Str::lower($role))) {
            return response()->json(['message' => 'Forbidden for this role'], 403);
        }

        return $next($request);
    }
}
