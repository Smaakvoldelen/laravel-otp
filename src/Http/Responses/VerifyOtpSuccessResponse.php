<?php

namespace Smaakvoldelen\Otp\Http\Responses;

use Illuminate\Http\JsonResponse;
use Smaakvoldelen\Otp\Contracts\VerifyOtpSuccessResponse as VerifyOtpSuccessResponseContract;
use Smaakvoldelen\Otp\Otp;
use Symfony\Component\HttpFoundation\Response;

class VerifyOtpSuccessResponse implements VerifyOtpSuccessResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request): Response
    {
        return $request->wantsJson()
            ? new JsonResponse('', 204)
            : redirect()->intended(Otp::redirects('login'));
    }
}
