<?php

namespace Ayoub\SiteLock\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Ayoub\SiteLock\SiteLock;

class SiteLockController extends Controller
{
    public function toggle(Request $request)
    {
        $state = $request->input('state');
        
        if ($state === 'lock') {
            SiteLock::lock();
            $message = 'Site has been locked';
        } elseif ($state === 'unlock') {
            SiteLock::unlock();
            $message = 'Site has been unlocked';
        } else {
            SiteLock::toggle();
            $message = SiteLock::isLocked() ? 'Site has been locked' : 'Site has been unlocked';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'locked' => SiteLock::isLocked()
            ]);
        }

        return back()->with('status', $message);
    }
}