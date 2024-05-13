<?php

namespace Workbench\App\Actions;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Smaakvoldelen\Otp\Contracts\UpdateUser as UpdateUserContract;
use Smaakvoldelen\Otp\Otp;

class UpdateUser implements UpdateUserContract
{
    public function update(User $user, array $input): bool
    {
        Validator::make($input, [
            Otp::username() => ['required', 'string', 'email', 'max:255', Rule::unique(\Workbench\App\Models\User::class)->ignoreModel($user)],
        ])->validate();

        return $user->update([
            Otp::username() => $input[Otp::username()],
        ]);
    }
}
