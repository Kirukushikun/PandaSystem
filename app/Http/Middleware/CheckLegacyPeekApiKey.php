<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLegacyPeekApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('services.legacy_peek.key');
        $provided = (string) $request->header('x-api-key');

        if (empty($expected) || !hash_equals($expected, $provided)) {
            abort(401, 'Invalid or missing API key.');
        }

        return $next($request);
    }
}
