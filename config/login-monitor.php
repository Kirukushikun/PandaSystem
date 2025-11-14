<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Login Monitor Host
    |--------------------------------------------------------------------------
    |
    | The host URL where login monitoring beacons will be sent.
    |
    */
    'host' => env('LOGIN_MONITOR_HOST', 'https://monitor.example.com'),

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable Monitoring
    |--------------------------------------------------------------------------
    |
    | Toggle login monitoring on/off.
    |
    */
    'enabled' => env('LOGIN_MONITOR_ENABLED', true),
];