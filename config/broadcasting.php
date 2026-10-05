<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    | Supported: "pusher", "ably", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_DRIVER', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over websockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */

    'connections' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'socketio' => [
            'driver' => 'pusher', // Keep 'pusher' driver, but this works for Socket.io
            'key' => env('PUSHER_APP_KEY'),  // You can set this to any key, it's not actually used
            'secret' => env('PUSHER_APP_SECRET'), // Not used
            'app_id' => env('PUSHER_APP_ID'), // Not used
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => false,
                'host' => '127.0.0.1',
                'port' => 6001,
                'scheme' => 'http',
            ],
        ],

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY', 'local'),
            'secret' => env('PUSHER_APP_SECRET', 'local'),
            'app_id' => env('PUSHER_APP_ID', 'local'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => env('PUSHER_APP_USE_TLS', false),
                'host' => env('LARAVEL_WEBSOCKETS_HOST', '127.0.0.1'),
                'port' => env('LARAVEL_WEBSOCKETS_PORT', 6001),
                'scheme' => env('LARAVEL_WEBSOCKETS_SCHEME', 'http'),
                'encrypted' => false,
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

        'options' => [
            'cluster' => 'mt1',
            'useTLS' => false,  // Disable TLS if using local server
            'host' => env('LARAVEL_WEBSOCKETS_HOST', '127.0.0.1'),
            'port' => env('LARAVEL_WEBSOCKETS_PORT', 6001), // Default WebSocket port
            'scheme' => env('LARAVEL_WEBSOCKETS_SCHEME', 'http'),
        ],


    ],

];
