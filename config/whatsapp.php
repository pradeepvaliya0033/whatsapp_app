<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Business API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for WhatsApp Business API integration
    |
    */

    'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v21.0'),
    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
    'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
    'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
    'app_secret' => env('WHATSAPP_APP_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    */

    'default_language' => env('WHATSAPP_DEFAULT_LANGUAGE', 'en_US'),
    'timeout' => env('WHATSAPP_TIMEOUT', 30),

    // SSL verification: set to false only for local dev. Prefer setting a CA bundle path.
    'verify_ssl' => env('WHATSAPP_VERIFY_SSL', true),
    'ca_bundle' => env('WHATSAPP_CA_BUNDLE', null), // e.g., storage_path('certs/cacert.pem')

    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    */

    'webhook_verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN'),
    'webhook_url' => env('WHATSAPP_WEBHOOK_URL'),
];
