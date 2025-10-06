<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Site Locked
    |--------------------------------------------------------------------------
    |
    | Determines whether the site is locked. This value is read from the
    | PERFORMANCE_LOCKED environment variable in your .env file.
    |
    */
    'locked' => env('PERFORMANCE_LOCKED', false),

    /*
    |--------------------------------------------------------------------------
    | Lock Message
    |--------------------------------------------------------------------------
    |
    | The message displayed when the site is locked.
    |
    */
    'lock_message' => 'This site is locked until payment.',

    /*
    |--------------------------------------------------------------------------
    | Lock Title
    |--------------------------------------------------------------------------
    |
    | The title displayed when the site is locked.
    |
    */
    'lock_title' =>  'Site Locked',
];