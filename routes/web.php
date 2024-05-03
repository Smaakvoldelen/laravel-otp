<?php

use Illuminate\Support\Facades\Route;
use Smaakvoldelen\Otp\Http\Controllers\SendOTPController;
use Smaakvoldelen\Otp\Otp;

Route::group(['middleware' => config('otp.middleware', ['web'])], function () {
    $enableViews = config('otp.views', true);

    if ($enableViews) {
        Route::get(Otp::route('login', '/login'), [SendOTPController::class, 'create'])
            ->middleware(['guest:'.config('otp.guard')])
            ->name('login');
    }

    Route::post(Otp::route('login', '/login'), [SendOTPController::class, 'store'])
        ->name('login.store');
});
