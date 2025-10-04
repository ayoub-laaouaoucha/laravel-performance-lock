<?php

use Illuminate\Support\Facades\Route;
use Ayoub\SiteLock\Http\Controllers\SiteLockController;

Route::post('/site-lock/toggle', [SiteLockController::class, 'toggle'])
    ->name('site-lock.toggle');