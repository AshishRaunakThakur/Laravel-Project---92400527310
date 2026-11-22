<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        abort_unless($request->user() && $request->user()->is_active, 403, 'Your account is inactive.');
        abort_unless(in_array($request->user()->role, $roles, true), 403, 'You do not have permission to view this page.');
        return $next($request);
    }
}
