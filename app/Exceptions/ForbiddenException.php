<?php

namespace App\Exceptions;

use App\Enums\ErrorCode;

class ForbiddenException extends BaseException
{
    protected $code = 403;
    protected $error = ErrorCode::FORBIDDEN;
}
