<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Facebook App Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Facebook App settings. You will need to
    | create a Facebook App at https://developers.facebook.com/apps/
    | and get your App ID and App Secret.
    |
    */

    'app_id' => env('FACEBOOK_APP_ID'),
    'app_secret' => env('FACEBOOK_APP_SECRET'),
    'redirect_uri' => env('FACEBOOK_REDIRECT_URI', env('APP_URL') . '/facebook/callback'),
    'api_version' => env('FACEBOOK_API_VERSION', 'v18.0'),

    /*
    |--------------------------------------------------------------------------
    | Facebook Scopes
    |--------------------------------------------------------------------------
    |
    | Define the permissions your app needs from Facebook users.
    | Common scopes include:
    | - pages_manage_metadata: Manage page metadata
    | - pages_read_engagement: Read page engagement data
    | - pages_show_list: Show list of pages
    | - pages_manage_posts: Manage page posts
    | - pages_read_user_content: Read user content on pages
    |
    */

    'scopes' => [
        'pages_manage_metadata',
        'pages_read_engagement',
        'pages_show_list',
        'pages_manage_posts',
        'pages_read_user_content',
    ],

    /*
    |--------------------------------------------------------------------------
    | Token Settings
    |--------------------------------------------------------------------------
    |
    | Configure token refresh and validation settings.
    |
    */

    'token_refresh_threshold' => env('FACEBOOK_TOKEN_REFRESH_THRESHOLD', 7), // days before expiry
    'auto_refresh_tokens' => env('FACEBOOK_AUTO_REFRESH_TOKENS', true),
];
