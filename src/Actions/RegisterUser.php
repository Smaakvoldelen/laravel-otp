<?php

namespace Smaakvoldelen\Otp\Actions;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Smaakvoldelen\Otp\Contracts\CreateNewUser;

class RegisterUser
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected CreateNewUser $creator)
    {
        //
    }

    /**
     * Handle the incoming request
     */
    public function handle(Request $request, callable $next): mixed
    {
        event(new Registered($this->creator->create($request->all())));

        return $next($request);
    }
}
