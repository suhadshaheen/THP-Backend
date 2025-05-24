<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $roles  (Comma-separated roles: "Admin,Freelancer,...")
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
{
    $user = auth('api')->user();

    if (!$user || !$user->role || !in_array($user->role->name, $roles)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    return $next($request);
}

}
