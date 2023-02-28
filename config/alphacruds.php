<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AlphaCruds Default Language
    |--------------------------------------------------------------------------
    |
    | Language that will be used if there is no other set.
    |
    */
    'default_language' => 'en',

    /*
    |--------------------------------------------------------------------------
    | AlphaCruds Back Path
    |--------------------------------------------------------------------------
    |
    | Path to redirect back from module views.
    |
    */
    'redirect_back_path' => '/',

    /*
    |--------------------------------------------------------------------------
    | AlphaCruds Routes
    |--------------------------------------------------------------------------
    |
    | Section to manage everything related with the admin panel routes.
    |
    */
    'routes' => [

        /*
        |--------------------------------------------------------------------------
        | AlphaCruds Panel Path
        |--------------------------------------------------------------------------
        |
        | This is the URI path where AlphaNews panel will be accessible from.
        |
        */
        'path' => 'alphacruds',

        /*
        |--------------------------------------------------------------------------
        | AlphaCruds Panel Route Middleware
        |--------------------------------------------------------------------------
        |
        | These middleware will get attached onto each AlphaNews panel route.
        |
        */
        'middleware' => ['web', 'auth:web'],

        /*
         |--------------------------------------------------------------------------
         | AlphaCruds Route Name Prefix
         |--------------------------------------------------------------------------
         |
         | This is the name prefix with which every name of admin panel route
         | will start.
         |
         */
        'route_name_prefix' => 'alphacruds',
    ],
];
