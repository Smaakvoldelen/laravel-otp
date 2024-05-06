<?php

namespace Smaakvoldelen\Otp\Actions;

use BadMethodCallException;
use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Smaakvoldelen\Otp\LoginRateLimiter;
use Smaakvoldelen\Otp\Models\Concerns\OtpAuthenticatable;
use Smaakvoldelen\Otp\Otp;

class SendOtp
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected StatefulGuard $guard, protected LoginRateLimiter $limiter)
    {
        //
    }

    /**
     * Handle the incoming request
     */
    public function handle(Request $request, callable $next): mixed
    {
        /** @var Authenticatable|null $user */
        $user = $this->validateCredentials($request);
        // @codeCoverageIgnoreStart
        if (! in_array(OtpAuthenticatable::class, class_uses_recursive($user))) {
            throw new BadMethodCallException($user::class.' does not implement '.OtpAuthenticatable::class);
        }
        // @codeCoverageIgnoreEnd

        // @phpstan-ignore-next-line
        $token = $user->generateOtp();

        // @phpstan-ignore-next-line
        $user->sendOtpNotification($token->token);

        $request->session()->put([
            'login.id' => $user->getKey(),
            'login.remember' => $request->boolean('remember'),
        ]);

        return $next($request);
    }

    /**
     * Fire the failed authentication attempt event with the given arguments.
     */
    protected function fireFailedEvent(Request $request, $user = null): void
    {
        event(new Failed(config('otp.guard'), $user, [
            Otp::username() => $request->{Otp::username()},
        ]));
    }

    /**
     * Attempt to validate the incoming credentials.
     */
    protected function validateCredentials(Request $request): Authenticatable
    {
        // @phpstan-ignore-next-line
        $model = $this->guard->getProvider()->getModel();

        return tap($model::where(Otp::username(), '=', $request->{Otp::username()})->first(), function ($user) use ($request) {
            if (! $user) {
                $this->fireFailedEvent($request, $user);

                $this->throwFailedAuthenticationException($request);
            }
        });
    }

    /**
     * Throw a failed authentication validation exception.
     */
    protected function throwFailedAuthenticationException(Request $request): void
    {
        $this->limiter->increment($request);

        throw ValidationException::withMessages([
            Otp::username() => [trans('auth.failed')],
        ]);
    }
}
