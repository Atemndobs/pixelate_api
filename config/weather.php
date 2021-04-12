<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api key for openweathermap
    |--------------------------------------------------------------------------
    |
    */

    'api' => [
        'key' => env('WEATHER_API_KEY', 'd75bc4e671fb7b4d190dd8a7c789183c'),
        'base_url' => env('WEATHER_BASE_URL', 'https://api.openweathermap.org/data/2.5/')
    ],

];
