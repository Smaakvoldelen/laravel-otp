<?php

namespace Smaakvoldelen\Otp\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Smaakvoldelen\Otp\Otp;

class CanonicalizeUsername
{
    /**
     * Handle the incoming request
     */
    public function handle(Request $request, callable $next): mixed
    {
        $request->merge([
            Otp::username() => Str::lower($request->{Otp::username()}),
        ]);

        return $next($request);
    }
}
