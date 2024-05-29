<?php

return [

    /*
    |--------------------------------------------------------------------------
    | JWT Driver
    |--------------------------------------------------------------------------
    |
    | This option defines the driver that will be used for JWT authentication.
    |
    */

    'driver' => 'tymon.jwt.driver.openssl', // Change if needed (e.g., 'firebase')

    /*
    |--------------------------------------------------------------------------
    | JWT Secret
    |--------------------------------------------------------------------------
    |
    | This option defines the secret key that will be used to sign and verify JWTs.
    | It's critical to keep this secret secure. Refer to Laravel's documentation
    | on environment variables for secure storage.
    |
    */

    'secret' => env('JWT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | JWT Blacklisting Enabled
    |--------------------------------------------------------------------------
    |
    | This option enables the ability to blacklist JWT tokens which have been
    | invalidated. This option may come in handy if you want to implement
    | a refresh token system or other security measures.
    |
    */

    'blacklist_enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | JWT TTL (Time to Live)
    |--------------------------------------------------------------------------
    |
    | This option defines the number of minutes a JWT token will be considered valid
    | after it has been issued. You may adjust this value based on your security
    | requirements.
    |
    */

    'ttl' => 60, // Adjust as needed

    /*
    |--------------------------------------------------------------------------
    | JWT Refresh TTL (Time to Live)
    |--------------------------------------------------------------------------
    |
    | This option defines the number of minutes a refresh token will be considered
    | valid after it has been issued. You may adjust this value based on your
    | security requirements.
    |
    */

    'refresh_ttl' => 240, // Adjust as needed (optional)

    /*
    |--------------------------------------------------------------------------
    | JWT Algorithm
    |--------------------------------------------------------------------------
    |
    | This option defines the cryptographic algorithm that will be used to encode
    | and decode the JWT tokens. Supported algorithms are listed below:
    |
    | - HS256
    | - HS384
    | - HS512
    | - RS256
    | - RS384
    | - RS512
    | - EC256
    | - EC384
    | - EC512
    | - OAEP with RSA-PSS
    | - A128CBC-HS256 (DIR)
    | - A256CBC-HS512 (DIR)
    |
    */

    'algorithm' => 'hs256', // Adjust if needed

    /*
    |--------------------------------------------------------------------------
    | JWT User Model
    |--------------------------------------------------------------------------
    |
    | This option defines the model name of the user that will be attached to
    | the JWT token. The package will attempt to find this model based on
    | its namespace.
    |
    */

    'user' => 'App\Models\User',  // Replace with your user model path

    /*
    |--------------------------------------------------------------------------
    | JWT Blacklisting Strategy
    |--------------------------------------------------------------------------
    |
    | This option defines the strategy that will be used to blacklist JWT tokens.
    | You may adjust this value based on your security requirements.
    |
    | Supported strategies: database, redis, null
    |
    */

    'blacklist_strategy' => 'database', // 'redis' or 'null' are also options

    /*
    |--------------------------------------------------------------------------
    | JWT Provider
    |
    */
];