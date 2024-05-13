<?php

namespace Smaakvoldelen\Otp\Http\Responses;

use Illuminate\Http\JsonResponse;
use Smaakvoldelen\Otp\Contracts\UpdateUserResponse as UpdateUserResponseContract;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserResponse implements UpdateUserResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request): Response
    {
        return $request->wantsJson()
            ? new JsonResponse('', 200)
            : back()->with('status', 'Profile updated');
    }
}
