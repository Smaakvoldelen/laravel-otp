<?php

use Smaakvoldelen\Otp\Otp;
use Workbench\App\Actions\CreateNewUser;

beforeEach(function () {
    Otp::registerView(fn () => response('OK'));

    Otp::createUsersUsing(CreateNewUser::class);
});

it('register screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

it('users can register using the register screen', function () {
    $response = $this->post(route('register.store'), [
        'email' => 'testuser@example.com',
    ]);

    $response->assertRedirect(route('login.verify'));
});

it('users can request an otp using json', function () {
    $response = $this->postJson(route('register.store'), [
        'email' => 'testuser@example.com',
    ]);

    $response->assertCreated();
});
