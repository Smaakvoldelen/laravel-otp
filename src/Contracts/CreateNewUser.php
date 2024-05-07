<?php

namespace Smaakvoldelen\Otp\Contracts;

use Illuminate\Foundation\Auth\User;

interface CreateNewUser
{
    /**
     * Validate and create a newly registered user.
     */
    public function create(array $input): User;
}
