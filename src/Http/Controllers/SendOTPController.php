<?php

namespace Smaakvoldelen\Otp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Smaakvoldelen\Otp\Contracts\SendOtpViewResponse;
use Smaakvoldelen\Otp\Http\Requests\SendOtpRequest;

class SendOTPController extends Controller
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
     *
     * @return void
     */
    public function store(SendOtpRequest $request)
    {
        // TODO: implement store() method.
    }
}
