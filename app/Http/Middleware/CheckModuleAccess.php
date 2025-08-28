<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    public function handle(Request $request, Closure $next, $module): Response
    {
        $user = $request->user();

        if (!$user || !$this->hasAccess($user, $module)) {
            abort(403, 'Unauthorized access to this module.');
        }

        return $next($request);
    }

    protected function hasAccess($user, $module): bool
    {
        // Directly access as array
        $access = $user->access;

        $key = strtoupper($module) . '_Module';

        return !empty($access[$key]) && $access[$key] === true;
    }
}
