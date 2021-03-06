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
    | Supported: "pusher", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_DRIVER', 'pusher'),

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
    // laravel-websocket
    'connections' => [
          'pusher' => [
            'driver' => 'pusher',
            'key' => env('ECHO_APP_KEY'),
            'secret' => env('ECHO_APP_SECRET'),
            'app_id' => env('ECHO_APP_ID'),
            'options' => [
                 'cluster' => env('ECHO_APP_CLUSTER'),
                 'encrypted' => true,
                'host' => '127.0.0.1',
                'debug'=> true,
                'port' => 6001,
                'scheme' => 'http'
            ],
          ],

          'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
          ],

          'log' => [
            'driver' => 'log',
          ],

          'null' => [
            'driver' => 'null',
          ],

    ],

];
