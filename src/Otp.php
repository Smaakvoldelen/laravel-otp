<?php

namespace Smaakvoldelen\Otp;

use Smaakvoldelen\Otp\Contracts\SendOtpViewResponse;
use Smaakvoldelen\Otp\Http\Responses\SimpleViewResponse;

class Otp
{
    public final function __construct()
    {
        //
    }

    /**
     * Indicate if Laravel OTP should register routes.
     */
    public static bool $registerRoutes = true;

    /**
     * Get the name of the email address request variable / field.
     */
    public static function email(): string
    {
        return config('otp.email', 'email');
    }

    /**
     * Configure Laravel OTP without registering routes.
     */
    public static function ignoreRoutes(): static
    {
        static::$registerRoutes = false;

        return new static;
    }

    /**
     * Get a completion redirect path for a specific feature.
     */
    public static function redirects(string $redirect, ?string $default = null): string
    {
        return config('otp.redirects.'.$redirect) ?? $default ?? config('otp.home');
    }

    /**
     * Get the route path for the given route name.
     */
    public static function route(string $routeName, string $default): string
    {
        return config('otp.routes.'.$routeName) ?? $default;
    }

    /**
     * Specify which view should be used as the send one-time password view.
     */
    public static function sendOtpView($view): void
    {
        app()->singleton(SendOtpViewResponse::class, function () use ($view) {
            return new SimpleViewResponse($view);
        });
    }

    /**
     * Get the username used for authentication.
     */
    public static function username(): string
    {
        return config('otp.username', 'email');
    }
}
