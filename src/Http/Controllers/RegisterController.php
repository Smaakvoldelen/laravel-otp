<?php

namespace Smaakvoldelen\Otp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Routing\Controller;
use Smaakvoldelen\Otp\Actions\CanonicalizeUsername;
use Smaakvoldelen\Otp\Actions\RegisterUser;
use Smaakvoldelen\Otp\Actions\SendOtp;
use Smaakvoldelen\Otp\Contracts\RegisterResponse;
use Smaakvoldelen\Otp\Contracts\RegisterViewResponse;

class RegisterController extends Controller
{
    /**
     * Show the registration view.
     */
    public function create(Request $request): RegisterViewResponse
    {
        return app(RegisterViewResponse::class);
    }

    /**
     * Create a new user.
     */
    public function store(Request $request): RegisterResponse
    {
        return $this->registerPipeline($request)->then(function (Request $request) {
            return app(RegisterResponse::class);
        });
    }

    /**
     * Register pipeline.
     */
    protected function registerPipeline(Request $request): Pipeline
    {
        return (new Pipeline(app()))->send($request)->through(array_filter([
            config('otp.lowercase_usernames') ? CanonicalizeUsername::class : null,
            RegisterUser::class,
            SendOtp::class,
        ]));
    }
}
