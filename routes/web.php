<?php

use Illuminate\Support\Facades\Route;
use Naqla\PerformanceLock\Http\Controllers\PerformanceLockController;
use Naqla\PerformanceLock\PerformanceLock;

Route::post('/performance-lock/toggle', [PerformanceLockController::class, 'toggle'])
    ->name('performance-lock.toggle');

// Lock route (anyone can lock)
Route::get('/lock', function () {
    PerformanceLock::lock();
    return redirect('/')
        ->with('status', config('performance-lock.lock_title') . ' 🔒');
})->name('performance-lock.lock');

// Unlock route using code from config
Route::get('/unlock/{code}', function ($code) {
    $unlockCode = config('performance-lock.unlock_code');

    if ($code !== $unlockCode) {
        abort(404, 'Invalid unlock code.');
    }

    PerformanceLock::unlock();

    return redirect('/')
        ->with('status', config('performance-lock.lock_title') . ' unlocked 🔓');
})->name('performance-lock.unlock');
