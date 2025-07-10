<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Honeypot
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->filled('_hp')) {
            abort(403, 'Bot detected!');
        }

        if (time() - $request->input('_hptime', 0) < 5) {
            abort(403, 'Too fast!');
        }

        return $next($request);
    }
}
