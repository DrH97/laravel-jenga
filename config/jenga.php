<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Bill IPN
    |--------------------------------------------------------------------------
    |
    | Configs needed for the bill ipn
    |
    */
    'bill' => [
        'username' => 'Equity',
        'password' => '3pn!Ty@zoi9',
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Whether to log in the library
    |
    */
    'logging' => [
        'enabled' => env('JENGA_ENABLE_LOGGING', false),
        'channels' => [
            'single', 'stderr',
        ],
    ],
];
