<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Smaakvoldelen\Otp\Enums\OTPType;
use Smaakvoldelen\Otp\Models\Otp;
use Smaakvoldelen\Otp\Notifications\OtpNotification;

uses(RefreshDatabase::class);

it('can generate numeric token', function () {
    $this->assertDatabaseEmpty('otps');

    $otp = $this->testUser->generateOtp();

    expect($otp)
        ->toBeInstanceOf(Otp::class)
        ->and($otp->token)
        ->toBeString()
        ->toBeNumeric();

    $this->assertDatabaseCount('otps', 1);
});

it('can generate alphanumeric token', function () {
    $this->assertDatabaseEmpty('otps');

    $otp = $this->testUser->generateOtp(type: OTPType::ALPHANUMERIC);

    expect($otp)
        ->toBeInstanceOf(Otp::class)
        ->and($otp->token)
        ->toBeString()
        ->toBeAlphaNumeric();

    $this->assertDatabaseCount('otps', 1);
});

it('can validate an one-time password', function () {
    $otp = $this->testUser->generateOtp();
    $optValid = $this->testUser->validateOtp($otp->token);

    expect($optValid)->toBeBool()->toBeTrue();

    $optInvalid = $this->testUser->validateOtp($otp->token, 'invalid');
    expect($optInvalid)->toBeBool()->toBeFalse();
});

it('can send an one-time password to the user', function () {
    OtpNotification::toMailUsing(null);

    Notification::fake();

    Notification::assertNothingSent();

    $otp = $this->testUser->generateOtp();
    $this->testUser->sendOtpNotification($otp->token);

    Notification::assertSentTo($this->testUser, OtpNotification::class, function ($notification, $channels) {
        expect($channels)
            ->toContain('mail');

        $mailNotification = (object) $notification->toMail($this->testUser);
        expect($mailNotification->subject)
            ->toEqual(Lang::get('One-time password notification'));

        return true;
    });

    Notification::assertCount(1);
});

it('can send an one-time password to the user with a custom callback', function () {
    OtpNotification::toMailUsing(fn ($notifiable, $token) => (new MailMessage())->subject('Custom subject'));

    Notification::fake();

    Notification::assertNothingSent();

    $otp = $this->testUser->generateOtp();
    $this->testUser->sendOtpNotification($otp->token);

    Notification::assertSentTo($this->testUser, OtpNotification::class, function ($notification, $channels) {
        expect($channels)
            ->toContain('mail');

        $mailNotification = (object) $notification->toMail($this->testUser);
        expect($mailNotification->subject)
            ->toEqual('Custom subject');

        return true;
    });

    Notification::assertCount(1);
});
