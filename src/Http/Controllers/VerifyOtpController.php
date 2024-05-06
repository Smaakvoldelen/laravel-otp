<?php

namespace Smaakvoldelen\Otp\Http\Controllers;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Controller;
use Smaakvoldelen\Otp\Contracts\VerifyOtpFailedResponse;
use Smaakvoldelen\Otp\Contracts\VerifyOtpSuccessResponse;
use Smaakvoldelen\Otp\Contracts\VerifyOtpViewResponse;
use Smaakvoldelen\Otp\Http\Requests\VerifyOtpRequest;
use Symfony\Component\HttpFoundation\Response;

class VerifyOtpController extends Controller
{
    public function __construct(protected StatefulGuard $guard)
    {
    }

    /**
     * Show the verify one-time password view.
     */
    public function create(VerifyOtpRequest $request): VerifyOtpViewResponse
    {
        if (!$request->hasChallengedUser()) {
            throw new HttpResponseException(redirect()->route('login'));
        }

        return app(VerifyOtpViewResponse::class);
    }

    /**
     * Attempt to verify an OTP.
     */
    public function store(VerifyOtpRequest $request): Response
    {
        $user = $request->challengerUser();
        if (!$request->hasValidCode()){
            return app(VerifyOtpFailedResponse::class)->toResponse($request);
        }

        $this->guard->login($user, $request->remember());

        $request->session()->regenerate();

        return app(VerifyOtpSuccessResponse::class)->toResponse($request);
    }
}
