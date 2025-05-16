<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ClientAuth
{
    public function handle(Request $request, Closure $next)
    {
        // For non-API routes
        if (!$request->expectsJson()) {
            if (!session()->has('client_id') && !$request->cookie('client_id')) {
                session()->put('intended_url', $request->fullUrl());
                return redirect()->route('client.login');
            }
        }

        return $next($request);
    }
}
