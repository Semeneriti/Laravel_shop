<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !$user->hasAnyRole($roles)) {
            abort(403, 'У вас нет доступа к этой странице.');
        }

        return $next($request);
    }
}
