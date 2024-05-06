<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Smaakvoldelen\Otp\Otp;

uses(RefreshDatabase::class);

beforeEach(function () {
    Otp::verifyOtpView(fn () => response('OK'));
});

it('login verify screen can be rendered', function () {
    $user = \Workbench\App\Models\User::create([
        'email' => 'testuser@example.com',
    ]);

    $this->post(route('login.store'), [
        'email' => $user->email,
    ]);

    $response = $this->get(route('login.verify'));

    $response->assertOk();
});

it('user can verify an opt using the login verify screen', function () {
    $user = \Workbench\App\Models\User::create([
        'email' => 'testuser@example.com',
    ]);

    $code = $user->generateOtp();

    $this->post(route('login.store'), [
        'email' => $user->email,
    ]);

    $response = $this->post(route('login.verify.store'), [
        'code' => $code->token,
    ]);

    $response->assertRedirect(Otp::redirects('login'));
    $this->assertAuthenticated();
});

it('user can verify an opt using json', function () {
    $user = \Workbench\App\Models\User::create([
        'email' => 'testuser@example.com',
    ]);

    $code = $user->generateOtp();

    $this->postJson(route('login.store'), [
        'email' => $user->email,
    ]);

    $response = $this->postJson(route('login.verify.store'), [
        'code' => $code->token,
    ]);

    $response->assertNoContent();
    $this->assertAuthenticated();
});

it('should redirect to login if user did not request an otp', function () {
    $response = $this->get(route('login.verify'));

    $response->assertRedirect(route('login'));
});

it('should redirect to login verify if otp is invalid', function () {
    $response = $this->post(route('login.verify.store'), [
        'code' => 'invalid',
    ]);

    $response->assertRedirect(route('login.verify'));
    $response->assertSessionHasErrors(['code']);
});

it('should show error if otp is invalid using json', function () {
    $response = $this->postJson(route('login.verify.store'), [
        'code' => 'invalid',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['code']);
});
