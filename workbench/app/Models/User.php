<?php

namespace Workbench\App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Smaakvoldelen\Otp\Models\Concerns\OtpAuthenticatable;

class User extends Authenticatable
{
    use Notifiable, OtpAuthenticatable;

    protected $fillable = [
        'email',
    ];
}
