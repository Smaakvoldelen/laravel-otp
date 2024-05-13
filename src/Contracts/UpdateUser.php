<?php

namespace Smaakvoldelen\Otp\Contracts;

use Illuminate\Foundation\Auth\User;

interface UpdateUser
{
    /**
     * Validate and update the given user.
     */
    public function update(User $user, array $input): bool;
}
