<?php

namespace Smaakvoldelen\Otp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Smaakvoldelen\Otp\Contracts\VerifyOtpViewResponse;

class VerifyOtpController extends Controller
{
    /**
     * Show the verify one-time password view.
     */
    public function create(Request $request): VerifyOtpViewResponse
    {
        return app(VerifyOtpViewResponse::class);
    }
}
