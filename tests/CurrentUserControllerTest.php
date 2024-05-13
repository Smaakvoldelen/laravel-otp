<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Smaakvoldelen\Otp\Otp;
use Workbench\App\Actions\UpdateUser;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    Otp::updateUserUsing(UpdateUser::class);

    actingAs($this->testUser);
});

it('user can be updated', function () {
    expect($this->testUser->email)
        ->toEqual('test@localhost');

    $response = $this->put(route('user.update'), [
        'email' => 'testuser@example.com',
    ]);

    $response->assertRedirect()
        ->assertSessionHas('status');

    expect($this->testUser->email)
        ->toEqual('testuser@example.com');
});

it('user can be updated using json', function () {
    expect($this->testUser->email)
        ->toEqual('test@localhost');

    $response = $this->putJson(route('user.update'), [
        'email' => 'testuser@example.com',
    ]);

    $response->assertOk();

    expect($this->testUser->email)
        ->toEqual('testuser@example.com');
});
