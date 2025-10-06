<?php

use Illuminate\Support\Facades\Route;
use Naqla\PerformanceLock\Http\Controllers\PerformanceLockController;

Route::post('/performance-lock/toggle', [PerformanceLockController::class, 'toggle'])
    ->name('performance-lock.toggle');

// Lock route (anyone can lock)
Route::get('/lock', function() {
    \Naqla\PerformanceLock\PerformanceLock::lock();
    return redirect('/')->with('status', 'Site has been locked 🔒');
})->name('performance-lock.lock');

// Unlock route with secret code
Route::get('/unlock/{code}', function($code) {
    // Change 'mysecretcode' to your own secret
    if ($code !== 'show-me-the-money') {
        abort(404);
    }
    
    \Naqla\PerformanceLock\PerformanceLock::unlock();
    return redirect('/')->with('status', 'Site has been unlocked 🔓');
})->name('performance-lock.unlock');