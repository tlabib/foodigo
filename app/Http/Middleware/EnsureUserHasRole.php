<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $allowedRoles = Arr::where($roles, fn (string $role): bool => in_array($role, [
            User::ROLE_ADMIN,
            User::ROLE_CUSTOMER,
            User::ROLE_RIDER,
        ], true));

        if (! in_array($user->role, $allowedRoles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
