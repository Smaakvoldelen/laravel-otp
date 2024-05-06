<?php

namespace Smaakvoldelen\Otp\Actions;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Smaakvoldelen\Otp\Contracts\LockoutResponse;
use Smaakvoldelen\Otp\LoginRateLimiter;

class EnsureLoginIsNotThrottled
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected LoginRateLimiter $limiter)
    {
        //
    }

    /**
     * Handle the incoming request.
     */
    public function handle(Request $request, callable $next): mixed
    {
        if (! $this->limiter->tooManyAttempts($request)) {
            return $next($request);
        }

        event(new Lockout($request));

        return app(LockoutResponse::class);
    }
}
