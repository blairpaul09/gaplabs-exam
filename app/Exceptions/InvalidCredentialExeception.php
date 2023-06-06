<?php

namespace App\Exceptions;
use App\Enums\ErrorCode;

class InvalidCredentialExeception extends BaseException
{
    protected $code = 401;
    protected $error = ErrorCode::INVALID_CREDENTIAL;
}
