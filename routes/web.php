<?php

use Illuminate\Support\Facades\Route;
use Smaakvoldelen\Otp\Http\Controllers\LogoutController;
use Smaakvoldelen\Otp\Http\Controllers\RegisterController;
use Smaakvoldelen\Otp\Http\Controllers\SendOtpController;
use Smaakvoldelen\Otp\Http\Controllers\VerifyOtpController;
use Smaakvoldelen\Otp\Otp;

Route::group(['middleware' => config('otp.middleware', ['web'])], function () {
    $enableViews = config('otp.views', true);

    if ($enableViews) {
        Route::get(Otp::route('login', '/login'), [SendOtpController::class, 'create'])
            ->middleware(['guest:'.config('otp.guard')])
            ->name('login');

        Route::get(Otp::route('login-verify', '/login-verify'), [VerifyOtpController::class, 'create'])
            ->middleware(['guest:'.config('otp.guard')])
            ->name('login.verify');

        Route::get(Otp::route('register', '/signup'), [RegisterController::class, 'create'])
            ->middleware(['guest:'.config('otp.guard')])
            ->name('register');
    }

    Route::post(Otp::route('login', '/login'), [SendOtpController::class, 'store'])
        ->name('login.store');

    Route::post(Otp::route('login-verify', '/login-verify'), [VerifyOtpController::class, 'store'])
        ->name('login.verify.store');

    Route::post(Otp::route('logout', '/logout'), LogoutController::class)
        ->name('logout');

    Route::post(Otp::route('register', '/signup'), [RegisterController::class, 'store'])
        ->name('register.store');
});
