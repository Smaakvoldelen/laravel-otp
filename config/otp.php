<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OTP Expire
    |--------------------------------------------------------------------------
    |
    | Here you may specify the expiry time (in minutes) for one-time passwords.
    | This security feature keeps tokens short-lived so they have less time to
    | be guessed. You may change this as needed.
    |
    */

    'expire' => 10,

    /*
    |--------------------------------------------------------------------------
    | OTP Length
    |--------------------------------------------------------------------------
    |
    | Here you may specify the length of one-time passwords that will be
    | generated.
    |
    */

    'length' => 6,

    /*
    |--------------------------------------------------------------------------
    | OTP Type
    |--------------------------------------------------------------------------
    |
    | Here you may specify which one-time password type Laravel OTP will use
    | to generate one-time passwords.
    |
    | Supported Types: "numeric", "alphanumeric"
    |
    */

    'type' => 'numeric',

    /*
    |--------------------------------------------------------------------------
    | OTP Guard
    |--------------------------------------------------------------------------
    |
    | Here you may specify which authentication guard Laravel OTP will use
    " while authenticating users. This value should correspond with one of your
    | guards that is already present in your "auth" configuration file.
    |
    */

    'guard' => 'web',

    /*
    |--------------------------------------------------------------------------
    | OTP Routes Middleware
    |--------------------------------------------------------------------------
    |
    | Here you may specify which middleware Laravel OTP will assign to the
    | routes that it registers with the application. If necessary, you may
    | change these middleware but typically this provided default is preferred.
    |
    */

    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | OTP Model
    |--------------------------------------------------------------------------
    |
    | Here you may specify which one-time password model Laravel OTP will use
    | while creating and verifying one-time passwords. If necessary, you may
    | change these model but typically this provided default is preferred.
    |
    */

    'model' => \Smaakvoldelen\Otp\Models\Otp::class,

    /*
    |--------------------------------------------------------------------------
    | Register View Routes
    |--------------------------------------------------------------------------
    |
    | Here you may specify if the routes returning views should be disabled as
    | you may not need them when building your own application. This may be
    | especially true if you're writing a custom single-page application.
    |
    */

    'views' => true,

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Some of the Laravel OTP features are optional. You may disable the
    | features  by removing them from this array. You're free to only remove some
    | of these features or you can even remove all of these if you need to.
    |
    */

    'features' => [],
];
