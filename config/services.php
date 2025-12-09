<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'allure' => [
        // from Laravel container to Allure container (internal Docker DNS)
        'internal_base_url' => env('ALLURE_INTERNAL_BASE_URL', 'http://allure:5050'),
        // from browser to Allure (host/production URL)
        'public_base_url'   => env('ALLURE_PUBLIC_BASE_URL', 'http://localhost:5050'),
        // Set to true to use local Allure CLI instead of Docker service
        'use_local'         => env('ALLURE_USE_LOCAL', false),
    ],

];
