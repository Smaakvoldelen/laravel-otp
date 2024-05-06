<?php

use Smaakvoldelen\Otp\Otp;

beforeEach(function () {
    Otp::verifyOtpView(fn () => response('OK'));
});

it('login verify screen can be rendered', function () {
    $response = $this->get(route('login.verify'));

    $response->assertOk();
});
