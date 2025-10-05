<?php

namespace Naqla\PerformanceLock\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Naqla\PerformanceLock\PerformanceLock;

class PerformanceLockController extends Controller
{
    public function toggle(Request $request)
    {
        $state = $request->input('state');
        
        if ($state === 'lock') {
            PerformanceLock::lock();
            $message = 'Site has been locked';
        } elseif ($state === 'unlock') {
            PerformanceLock::unlock();
            $message = 'Site has been unlocked';
        } else {
            PerformanceLock::toggle();
            $message = PerformanceLock::isLocked() ? 'Site has been locked' : 'Site has been unlocked';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'locked' => PerformanceLock::isLocked()
            ]);
        }

        return back()->with('status', $message);
    }
}