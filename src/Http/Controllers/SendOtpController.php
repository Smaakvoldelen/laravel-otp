<?php

namespace Smaakvoldelen\Otp\Http\Controllers;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Pipeline;
use Smaakvoldelen\Otp\Actions\CanonicalizeUsername;
use Smaakvoldelen\Otp\Actions\EnsureLoginIsNotThrottled;
use Smaakvoldelen\Otp\Actions\SendOtp;
use Smaakvoldelen\Otp\Contracts\SendOtpViewResponse;
use Smaakvoldelen\Otp\Contracts\SentOtpResponse;
use Smaakvoldelen\Otp\Http\Requests\SendOtpRequest;

class SendOtpController extends Controller
{
    /**
     * Show the send one-time password view.
     */
    public function create(Request $request): SendOtpViewResponse
    {
        return app(SendOtpViewResponse::class);
    }

    /**
     * Attempt to send an OTP to the user.
     */
    public function store(SendOtpRequest $request): Responsable
    {
        return $this->sendOtpPipeline($request)->then(function ($request) {
            return app(SentOtpResponse::class);
        });
    }

    /**
     * Send the OTP pipeline.
     *
     * @param SendOtpRequest $request
     * @return Pipeline
     */
    protected function sendOtpPipeline(SendOtpRequest $request): Pipeline
    {
        return (new Pipeline(app()))->send($request)->through(array_filter([
            config('otp.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
            config('otp.lowercase_usernames') ? CanonicalizeUsername::class : null,
            SendOtp::class,
        ]));
    }
}
