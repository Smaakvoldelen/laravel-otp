<?php

namespace Smaakvoldelen\Otp\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Smaakvoldelen\Otp\Enums\OTPType;
use Smaakvoldelen\Otp\Models\Otp;
use Smaakvoldelen\Otp\Notifications\OtpNotification;

trait OtpAuthenticatable
{
    /**
     * Get all the OTPs for the model.
     */
    public function otps(): MorphMany
    {
        return $this->morphMany(config('otp.model', Otp::class), 'model');
    }

    /**
     * Generate an OTP token and save it to the database.
     */
    public function generateOtp(?string $identifier = null, ?int $length = null, ?int $expire = null, ?OTPType $type = null): Otp
    {
        $length = $length ?? config('otp.length', 6);
        $type = $type ?? OTPType::tryFrom(config('otp.type')) ?? OTPType::NUMERIC;

        $token = match ($type) {
            OTPType::NUMERIC => $this->generateNumericToken($length),
            OTPType::ALPHANUMERIC => $this->generateAlphanumericToken($length),
        };

        return $this->otps()->create([
            'identifier' => $identifier ?? $this->getOtpIdentifier(),
            'token' => $token,
            'expires_at' => now()->addMinutes($expire ?? config('otp.expire', 10)),
        ]);
    }

    /**
     * Get the OTP identifier.
     */
    public function getOtpIdentifier(): string
    {
        return $this->email;
    }

    /**
     * Send an OTP notification to the user.
     */
    public function sendOtpNotification(string $token): void
    {
        $this->notify(new OtpNotification($token));
    }

    /**
     * Validates an OTP token and updates the database accordingly.
     */
    public function validateOtp(string $token, ?string $identifier = null): bool
    {
        $otp = $this->otps()
            ->where('identifier', '=', $identifier ?? $this->getOtpIdentifier())
            ->where('token', '=', $token)
            ->whereNull('validated_at')
            ->first();

        if ($otp?->valid) {
            $otp->update([
                'validated_at' => now(),
            ]);

            return true;
        }

        return false;
    }

    /**
     * Generate an alphanumeric token of the specified length.
     */
    protected function generateAlphanumericToken(int $length): string
    {
        return Str::random($length);
    }

    /**
     * Generate a numeric token of the specified length.
     */
    protected function generateNumericToken(int $length): string
    {
        return (string) rand(10 ** ($length - 1), (10 ** $length) - 1);
    }
}
