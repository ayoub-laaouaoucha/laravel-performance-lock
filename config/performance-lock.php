<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Site Locked
    |--------------------------------------------------------------------------
    |
    | Determines whether the site is locked. This value is read from the
    | SITE_LOCKED environment variable in your .env file.
    |
    */
    'locked' => env('SITE_LOCKED', false),

    /*
    |--------------------------------------------------------------------------
    | Lock Message
    |--------------------------------------------------------------------------
    |
    | The message displayed when the site is locked.
    |
    */
    'lock_message' => env('SITE_LOCK_MESSAGE', 'This site is locked until payment.'),

    /*
    |--------------------------------------------------------------------------
    | Lock Title
    |--------------------------------------------------------------------------
    |
    | The title displayed when the site is locked.
    |
    */
    'lock_title' => env('SITE_LOCK_TITLE', 'Site Locked'),
];