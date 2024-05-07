<?php

namespace Workbench\App\Actions;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Smaakvoldelen\Otp\Contracts\CreateNewUser as CreateNewUserContract;
use Smaakvoldelen\Otp\Otp;

class CreateNewUser implements CreateNewUserContract
{
    public function create(array $input): User
    {
        Validator::make($input, [
            Otp::username() => ['required', 'string', 'email', 'max:255', Rule::unique(\Workbench\App\Models\User::class)],
        ])->validate();

        return \Workbench\App\Models\User::create([
            Otp::username() => $input[Otp::username()],
        ]);
    }
}
