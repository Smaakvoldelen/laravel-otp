<?php

namespace Smaakvoldelen\Otp\Http\Controllers;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Smaakvoldelen\Otp\Contracts\LogoutResponse;

class LogoutController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected StatefulGuard $guard)
    {
        //
    }

    /**
     * Destroy an authenticated session.
     */
    public function __invoke(Request $request): LogoutResponse
    {
        $this->guard->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return app(LogoutResponse::class);
    }
}
