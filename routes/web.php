<?php

use Illuminate\Support\Facades\Route;
use Smaakvoldelen\Otp\Http\Controllers\CurrentUserController;
use Smaakvoldelen\Otp\Http\Controllers\LogoutController;
use Smaakvoldelen\Otp\Http\Controllers\RegisterController;
use Smaakvoldelen\Otp\Http\Controllers\SendOtpController;
use Smaakvoldelen\Otp\Http\Controllers\VerifyOtpController;
use Smaakvoldelen\Otp\Otp;

Route::group(['middleware' => config('otp.middleware', ['web'])], function () {
    $enableViews = config('otp.views', true);

    // Authenticate user.
    if ($enableViews) {
        Route::get(Otp::route('login', '/login'), [SendOtpController::class, 'create'])
            ->middleware(['guest:'.config('otp.guard')])
            ->name('login');

        Route::get(Otp::route('login-verify', '/login-verify'), [VerifyOtpController::class, 'create'])
            ->middleware(['guest:'.config('otp.guard')])
            ->name('login.verify');
    }

    Route::post(Otp::route('login', '/login'), [SendOtpController::class, 'store'])
        ->name('login.store');

    Route::post(Otp::route('login-verify', '/login-verify'), [VerifyOtpController::class, 'store'])
        ->name('login.verify.store');

    Route::post(Otp::route('logout', '/logout'), LogoutController::class)
        ->name('logout');

    // Update user.
    Route::put(Otp::route('update-user', '/user'), [CurrentUserController::class, 'update'])
        ->name('user.update');

    // Register user.
    if ($enableViews) {
        Route::get(Otp::route('register', '/signup'), [RegisterController::class, 'create'])
            ->middleware(['guest:'.config('otp.guard')])
            ->name('register');
    }

    Route::post(Otp::route('register', '/signup'), [RegisterController::class, 'store'])
        ->name('register.store');
});
