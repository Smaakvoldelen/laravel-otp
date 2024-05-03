<?php

namespace Smaakvoldelen\Otp;

use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginRateLimiter
{
    /**
     * Create a new login rate limiter instance.
     */
    public function __construct(protected RateLimiter $limiter)
    {
        //
    }

    /**
     * Get the number of attempts for the given key.
     */
    public function attempts(Request $request): mixed
    {
        return $this->limiter->attempts($this->throttleKey($request));
    }

    /**
     * Determine the number of seconds until logging in is available again.
     */
    public function availableIn(Request $request): int
    {
        return $this->limiter->availableIn($this->throttleKey($request));
    }

    /**
     * Clear the login locks for the given user credentials.
     */
    public function clear(Request $request): void
    {
        $this->limiter->clear($this->throttleKey($request));
    }

    /**
     * Increment the login attempts for the user.
     */
    public function increment(Request $request): void
    {
        $this->limiter->hit($this->throttleKey($request), 60);
    }

    /**
     * Determine if the user has too many failed login attempts.
     */
    public function tooManyAttempts(Request $request): bool
    {
        return $this->limiter->tooManyAttempts($this->throttleKey($request), 5);
    }

    /**
     * Get the throttle key for the given request.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input(Otp::username())).'|'.$request->ip());
    }
}
