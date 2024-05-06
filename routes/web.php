<?php

use Illuminate\Support\Facades\Route;
use Smaakvoldelen\Otp\Http\Controllers\SendOtpController;
use Smaakvoldelen\Otp\Otp;

Route::group(['middleware' => config('otp.middleware', ['web'])], function () {
    $enableViews = config('otp.views', true);

    if ($enableViews) {
        Route::get(Otp::route('login', '/login'), [SendOtpController::class, 'create'])
            ->middleware(['guest:'.config('otp.guard')])
            ->name('login');

        Route::get(Otp::route('login-verify', '/login-verify'), fn () => 'OK')
            ->middleware(['auth:'.config('otp.guard')])
            ->name('login.verify');
    }

    Route::post(Otp::route('login', '/login'), [SendOtpController::class, 'store'])
        ->name('login.store');
});
