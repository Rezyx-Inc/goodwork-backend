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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'mandrill' => [
        'secret' => 'fp1ZEved5MaI8luTa2emWg',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'recaptcha' => [
        'sitekey' => env('RECAPTCHA_SITEKEY'),
        'secret' => env('RECAPTCHA_SECRET'),
    ],

    'linkedin-openid' => [
        'client_id' => '86aikrkrwln3m2',
        'client_secret' => 'FgreyLF9g2BhHf5n',
        'redirect' => 'http://127.0.0.1:8000/auth/linkedin/callback',

    ],

];
