<?php

use Illuminate\Contracts\Auth\Authenticatable;

it('user can logout', function () {
    Auth::guard()->setUser(
        Mockery::mock(Authenticatable::class)->shouldIgnoreMissing()
    );

    $response = $this->post(route('logout'));

    $response->assertRedirect('/');

    expect(Auth::guard()->getUser())
        ->toBeNull();
});

it('user can logout using json', function () {
    Auth::guard()->setUser(
        Mockery::mock(Authenticatable::class)->shouldIgnoreMissing()
    );

    $response = $this->postJson(route('logout'));

    $response->assertNoContent();

    expect(Auth::guard()->getUser())
        ->toBeNull();
});
