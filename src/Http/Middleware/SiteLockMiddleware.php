<?php

namespace Ayoub\SiteLock\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Ayoub\SiteLock\SiteLock;

class SiteLockMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Allow access to unlock route
        if ($request->routeIs('site-lock.toggle')) {
            return $next($request);
        }

        // Check if site is locked
        if (SiteLock::isLocked()) {
            return response()->view('site-lock::locked', [], 403);
        }

        return $next($request);
    }
}