<?php

namespace Naqla\PerformanceLock\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Naqla\PerformanceLock\PerformanceLock;

class PerformanceLockMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Allow access to unlock route
        if ($request->routeIs('performance-lock.toggle')) {
            return $next($request);
        }

        // Check if site is locked
        if (PerformanceLock::isLocked()) {
            return response()->view('performance-lock::locked', [], 403);
        }

        return $next($request);
    }
}