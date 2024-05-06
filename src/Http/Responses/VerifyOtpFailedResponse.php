<?php

namespace Smaakvoldelen\Otp\Http\Responses;

use Illuminate\Validation\ValidationException;
use Smaakvoldelen\Otp\Contracts\VerifyOtpFailedResponse as VerifyOtpFailedResponseContract;
use Symfony\Component\HttpFoundation\Response;

class VerifyOtpFailedResponse implements VerifyOtpFailedResponseContract
{
    public function toResponse($request): Response
    {
        [$key, $message] = ['code', trans('The provided one-time password was invalid.')];
        if ($request->wantsJson()) {
            throw ValidationException::withMessages(([
                $key => [$message],
            ]));
        }

        return redirect()
            ->route('login.verify')
            ->withErrors([$key => $message]);
    }
}
