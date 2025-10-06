<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Lock Message
    |--------------------------------------------------------------------------
    |
    | The message displayed when the site is locked.
    |
    */
    'lock_message' => env('SITE_LOCK_MESSAGE', 'This site is locked until payment is received.'),

    /*
    |--------------------------------------------------------------------------
    | Lock Title
    |--------------------------------------------------------------------------
    |
    | The title displayed when the site is locked.
    |
    */
    'lock_title' => env('SITE_LOCK_TITLE', 'Site Locked'),

    /*
    |--------------------------------------------------------------------------
    | Unlock Secret Code
    |--------------------------------------------------------------------------
    |
    | The secret code required to unlock the site.
    |
    */
    'unlock_code' => env('SITE_UNLOCK_CODE', 'show-me-the-money'),
];