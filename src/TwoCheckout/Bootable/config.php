<?php

/**
 * 2Checkout API Creds/Settings
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Integration Mode
    |--------------------------------------------------------------------------
    |
    | This value is the name of your integration.
    | Default : sandbox 
    | Other : production
    */
    'env' => env('2CO_ENV','sandbox'),

    /*
    |--------------------------------------------------------------------------
    | 2Checkout Seller ID
    |--------------------------------------------------------------------------
    |
    | This value represents your 2Checkout Account Number.
    */
    'seller_id' => env('2CO_SELLER_ID'), // Alias:Account Number

    /*
    |--------------------------------------------------------------------------
    | 2Checkout Username
    |--------------------------------------------------------------------------
    |
    | This value represents your 2Checkout Login Username.
    */
    'username' => env('2CO_USERNAME'),

    /*
    |--------------------------------------------------------------------------
    | 2Checkout Password
    |--------------------------------------------------------------------------
    |
    | This value represents your 2Checkout Login Password.
    */
    'password' => env('2CO_PASS'),

    /*
    |--------------------------------------------------------------------------
    | 2Checkout Public Key
    |--------------------------------------------------------------------------
    |
    | This value represents your 2Checkout API Public KEY.
    */
    'publishable_key' => env('2CO_PUB_KEY'),

    /*
    |--------------------------------------------------------------------------
    | 2Checkout Private Key
    |--------------------------------------------------------------------------
    |
    | This value represents your 2Checkout API Private KEY.
    */
    'private_key' => env('2CO_PRV_KEY'),

    /*
    |--------------------------------------------------------------------------
    | 2Checkout Secert Word
    |--------------------------------------------------------------------------
    |
    | This value represents your 2Checkout API Secert Word (used to verify webhooks events).
    */
    'secret_word' => env('2CO_SECERT'),

    /*
    |--------------------------------------------------------------------------
    | 2Checkout Default Currency
    |--------------------------------------------------------------------------
    |
    | This value represents your 2Checkout Account Default Currency.
    | @todo : Accept Array of Currencies.
    */
    'default_currency'=> env('2CO_CURRENCY', 'USD'),

    /*
    |--------------------------------------------------------------------------
    | 2Checkout SSL Verification
    |--------------------------------------------------------------------------
    |
    | This value represents your 2Checkout API Connection Type (Http or Https).
    | Default : true 
    | Other :  false , in case usage of sandbox mode
    */
    'verify_ssl' => env('2CO_SSL_ON', true),

    /*
    |--------------------------------------------------------------------------
    | 2Checkout SSL Cert Path
    |--------------------------------------------------------------------------
    |
    | This value represents your SSL Cert Path.
    | Default : default , represents no ssl cert (used for sandbox mode only) 
    | Other :  /path/to/ssl_cert
    */
    'ssl_cert_path' => env('2CO_SSL_PATH', 'default'),

    /*
    |--------------------------------------------------------------------------
    | 2Checkout API Version
    |--------------------------------------------------------------------------
    |
    | This value represents 2Checkout API Version.
    | Default : 0.3.1  
    */
    'api_version' => '0.3.1',
];