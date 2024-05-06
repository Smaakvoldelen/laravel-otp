<?php

namespace Smaakvoldelen\Otp\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Smaakvoldelen\Otp\Contracts\LockoutResponse as LockoutResponseContract;
use Smaakvoldelen\Otp\LoginRateLimiter;
use Smaakvoldelen\Otp\Otp;
use Symfony\Component\HttpFoundation\Response;

class LockoutResponse implements LockoutResponseContract
{
    /**
     * Create a new response instance.
     */
    public function __construct(protected LoginRateLimiter $limiter)
    {
        //
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     */
    public function toResponse($request): Response
    {
        return with($this->limiter->availableIn($request), function ($seconds) {
            throw ValidationException::withMessages([
                Otp::username() => [
                    trans('auth.throttle', [
                        'seconds' => $seconds,
                        'minutes' => ceil($seconds / 60),
                    ]),
                ],
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        });
    }
}
