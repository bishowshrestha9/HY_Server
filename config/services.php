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

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'twocheckout' => [
        'merchant_code' => env('TWOCHECKOUT_MERCHANT_CODE'), // Publishable Key
        'secret_key' => env('TWOCHECKOUT_SECRET_KEY'), // Private Key
        'buy_link_secret' => env('TWOCHECKOUT_BUY_LINK_SECRET'), // Secret Word for signature
        'publishable_key' => env('TWOCHECKOUT_PUBLISHABLE_KEY'),
        'private_key' => env('TWOCHECKOUT_PRIVATE_KEY'),
        'sandbox' => env('TWOCHECKOUT_SANDBOX', true),
    ],

    'payoneer' => [
        'env' => env('PAYONEER_ENV', 'sandbox'), // sandbox or live
        'merchant_code' => env('PAYONEER_MERCHANT_CODE'),
        'payment_token' => env('PAYONEER_PAYMENT_TOKEN'),
        'division' => env('PAYONEER_DIVISION'),
        'webhook_secret' => env('PAYONEER_WEBHOOK_SECRET'),
        'frontend_url' => env('FRONTEND_URL', 'http://localhost:3000'),
    ],

    'dodo' => [
        'mode' => env('DODO_PAYMENTS_MODE', 'test'),
        'api_key' => env('DODO_PAYMENTS_API_KEY'),
        'webhook_secret' => env('DODO_PAYMENTS_WEBHOOK_SECRET'),
        'product_id' => env('DODO_PAYMENTS_PRODUCT_ID'),
        'frontend_url' => env('FRONTEND_URL', 'http://localhost:3000'),
    ],

    'getpay' => [
        'pap_info' => env('GETPAY_PAP_INFO'),
        'opr_key' => env('GETPAY_OPR_KEY'),
        'ins_key' => env('GETPAY_INS_KEY'),
        'currency' => env('GETPAY_CURRENCY', 'NPR'),
        'base_url' => env('GETPAY_BASE_URL', 'https://getpay-uat.machbank.com/ecom-gateway/v1/secure-merchant/transactions'),
        'bundle_url' => env('GETPAY_BUNDLE_URL', 'https://minio.finpos.global/getpay-cdn/webcheckout/v5/bundle.js'),
        'frontend_url' => env('FRONTEND_URL', 'http://localhost:3000'),
        'success_url' => env('GETPAY_SUCCESS_URL', env('FRONTEND_URL', 'http://localhost:3000') . '/payment/success'),
        'failure_url' => env('GETPAY_FAILURE_URL', env('FRONTEND_URL', 'http://localhost:3000') . '/payment/failure'),
    ],


];
