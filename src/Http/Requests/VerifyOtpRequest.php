<?php

namespace Smaakvoldelen\Otp\Http\Requests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Smaakvoldelen\Otp\Contracts\VerifyOtpFailedResponse;
use Smaakvoldelen\Otp\LoginRateLimiter;

class VerifyOtpRequest extends FormRequest
{
    /**
     * The user attempting to log in using a one-time-password.
     */
    protected ?Authenticatable $challengerUser = null;

    /**
     * Indicates if the user should be remembered after login.
     */
    private bool $remember = false;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => ['nullable', 'string'],
        ];
    }

    /**
     * Get the user that is attempting to log in.
     */
    public function challengerUser(): Authenticatable
    {
        // @codeCoverageIgnoreStart
        if ($this->challengerUser) {
            return $this->challengerUser;
        }
        // @codeCoverageIgnoreEnd

        $model = app(StatefulGuard::class)->getProvider()->getModel();
        if (
            !$this->session()->has('login.id') ||
            !$user = $model::find($this->session()->get('login.id'))
        ) {
            throw new HttpResponseException(app(VerifyOtpFailedResponse::class)->toResponse($this));
        }

        return $this->challengerUser = $user;
    }

    /**
     * Determine if there is a challenged user in the current session.
     */
    public function hasChallengedUser(): bool
    {
        // @codeCoverageIgnoreStart
        if ($this->challengerUser) {
            return true;
        }
        // @codeCoverageIgnoreEnd

        $model = app(StatefulGuard::class)->getProvider()->getModel();

        return $this->session->has('login.id') && $model::find($this->session()->get('login.id'));
    }

    /**
     * Determine if the request has a valid code.
     */
    public function hasValidCode(): bool
    {
        return $this->code && tap($this->challengerUser()->validateOtp($this->code), function ($result) {
                $this->session()->forget('login.id');
                app(LoginRateLimiter::class)->clear($this);
            });
    }

    /**
     * Determine if the user wanted to be remembered after login.
     */
    public function remember(): bool
    {
        if (!$this->remember) {
            $this->remember = $this->session()->pull('login.remember', false);
        }

        return $this->remember;
    }
}
