<?php

namespace Smaakvoldelen\Otp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Smaakvoldelen\Otp\Otp
 */
class Otp extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Smaakvoldelen\Otp\Otp::class;
    }
}
