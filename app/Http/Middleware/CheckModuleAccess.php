<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    public function handle(Request $request, Closure $next, ...$modules): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        foreach ($modules as $module) {
            if ($this->hasAccess($user, $module)) {
                return $next($request); // ✅ Access granted if *any* module matches
            }
        }

        abort(403, 'Unauthorized access to this module.');
    }

    protected function hasAccess($user, $module): bool
    {
        $access = $user->access;
        $key = strtoupper($module) . '_Module';

        return !empty($access[$key]) && $access[$key] === true;
    }
}
