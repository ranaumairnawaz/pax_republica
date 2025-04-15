<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AcceptJsonOnAjax
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set Accept header for AJAX requests to receive JSON responses
        if ($request->ajax() || $request->wantsJson() || $request->isJson()) {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}
