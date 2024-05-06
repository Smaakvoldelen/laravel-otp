<?php

namespace Smaakvoldelen\Otp\Http\Responses;

use Illuminate\Http\JsonResponse;
use Smaakvoldelen\Otp\Contracts\LogoutResponse as LogoutResponseContract;
use Smaakvoldelen\Otp\Otp;
use Symfony\Component\HttpFoundation\Response;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request): Response
    {
        return $request->wantsJson()
            ? new JsonResponse('', 204)
            : redirect(Otp::redirects('logout'));
    }
}
