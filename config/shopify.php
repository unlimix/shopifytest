<?php

return [

    /*
    |--------------------------------------------------------------------------
    | General configuration
    |--------------------------------------------------------------------------
    */

    'app_type' => env('SHOPIFY_APP_TYPE', 'private'),

    /*
    |--------------------------------------------------------------------------
    | Private app configuration
    |--------------------------------------------------------------------------
    */

    'private_api_key' => env('SHOPIFY_PRIVATE_API_KEY'),
    'private_password' => env('SHOPIFY_PRIVATE_PASSWORD'),
    'private_hostname' => env('SHOPIFY_PRIVATE_HOSTNAME'),

    /*
    |--------------------------------------------------------------------------
    | Public app configuration
    |--------------------------------------------------------------------------
    */

    'api_key' => env('SHOPIFY_API_KEY'),
    'secret_key' => env('SHOPIFY_SECRET_KEY'),
    'appstore_uri' => env('SHOPIFY_APPSTORE_URI'),
    'scope' => env('SHOPIFY_SCOPE'),
    'redirect_uri' => 'shopify/install',

    /*
    |--------------------------------------------------------------------------
    | Webhooks and assets
    |--------------------------------------------------------------------------
    */

    'webhooks' => [
        [
            'topic' => 'app/uninstalled',
            'address' => 'api/shopify/webhooks/app/uninstalled'
        ]
    ],

    'assets' => [

    ]

];