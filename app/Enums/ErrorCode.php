<?php

namespace App\Enums;

enum ErrorCode : string
{
    case INVALID_CREDENTIAL = 'INVALID_CREDENTIAL';
    case FORBIDDEN = 'FORBIDDEN';

    public function description() : string
    {
        return match($this)
        {
            ErrorCode::INVALID_CREDENTIAL => 'These credentials do not match our records.',
            ErrorCode::FORBIDDEN => "You don't have authorization to perform this action.",
        };
    }
}
