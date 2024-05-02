<?php

namespace Smaakvoldelen\Otp\Enums;

enum OTPType: string
{
    case NUMERIC = 'numeric';
    case ALPHANUMERIC = 'alphanumeric';
}
