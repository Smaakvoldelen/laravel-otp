<?php

use Smaakvoldelen\Otp\LoginRateLimiter;
use Smaakvoldelen\Otp\Otp;

beforeEach(function () {
    Otp::sendOtpView(fn () => response('OK'));
});

it('login screen can be rendered', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
});

it('users can request an otp using the login screen', function () {
    $user = \Workbench\App\Models\User::create([
        'email' => 'testuser@example.com',
    ]);

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
    ]);

    $response->assertRedirect(route('login.verify'));
});

it ('users can request an otp using json', function() {
    $user = \Workbench\App\Models\User::create([
        'email' => 'testuser@example.com',
    ]);

    $response = $this->postJson(route('login.store'),[
        'email' => $user->email,
    ]);

    $response->assertNoContent();
});

it('invalid user cannot request an otp using the login screen', function () {
    $response =  $this->post(route('login.store'), [
        'email' => 'invalid',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors([
        Otp::username()
    ]);
});

it ('cannot request an otp if attempted too many times', function() {
    $this->mock(LoginRateLimiter::class, function($mock) {
        $mock->shouldReceive('tooManyAttempts')->andReturn(true);
        $mock->shouldReceive('availableIn')->andReturn(10);
    });

    $response = $this->postJson(route('login.store'), [
        'email' => 'invalid',
        'password' => 'secret',
    ]);

    $response->assertTooManyRequests();
    $response->assertJsonValidationErrors([
        Otp::username()
    ]);
});;
