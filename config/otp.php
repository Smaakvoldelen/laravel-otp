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
    | Username / Email
    |--------------------------------------------------------------------------
    |
    | This value defines which model attribute should be considered as your
    | application's "username" field. Typically, this might be the email
    | address of the users but you are free to change this value here.
    |
    | Out of the box, Laravel OTP expects sending one-time password requests to
    | have a field named 'email'. If the application uses another name for the
    | field you may define it below as needed.
    |
    */

    'username' => 'email',

    'email' => 'email',

    /*
    |--------------------------------------------------------------------------
    | Lowercase Usernames
    |--------------------------------------------------------------------------
    |
    | This value defines whether usernames should be lowercased before saving
    | them in the database, as some database system string fields are case
    | sensitive. You may disable this for your application if necessary.
    |
    */

    'lowercase_usernames' => true,

    /*
    |--------------------------------------------------------------------------
    | Home Path
    |--------------------------------------------------------------------------
    |
    | Here you may configure the path where users will get redirected during
    | authentication when the operations are successful and the user is
    | authenticated. You are free to change this value.
    |
    */

    'home' => '/',

    /*
    |--------------------------------------------------------------------------
    | OTP Routes Prefix / Subdomain
    |--------------------------------------------------------------------------
    |
    | Here you may specify which prefix Fortify will assign to all the routes
    | that it registers with the application. If necessary, you may change
    | subdomain under which all of the Laravel OTP routes will be available.
    |
    */

    'prefix' => '',

    'domain' => null,

    'redirects' => [
        'login' => null,
        'logout' => null,
    ],

    'routes' => [
        'login' => null,
        'login-verify' => null,
    ],

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
