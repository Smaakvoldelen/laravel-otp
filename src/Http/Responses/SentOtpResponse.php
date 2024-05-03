<?php

namespace Smaakvoldelen\Otp\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Smaakvoldelen\Otp\Contracts\SentOtpResponse as SendOtpResponseContract;
use Smaakvoldelen\Otp\Otp;
use Symfony\Component\HttpFoundation\Response;

class SentOtpResponse implements SendOtpResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     */
    public function toResponse($request): Response
    {
        return $request->wantsJson()
            ? new JsonResponse('', 204)
            : redirect()->intended(Otp::redirects('login'));
    }
}
