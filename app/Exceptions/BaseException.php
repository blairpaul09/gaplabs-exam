<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Enums\ErrorCode;

class BaseException extends Exception
{
    /**
     * @property \App\Enums\ErrorCode $error
     */
    protected $error;

    /**
     * @property int $code
     */
    protected $code;

    /**
     * Report the exception.
     */
    public function report(): void
    {
        // ...
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): Response
    {
        return response(
            [
                'error_message' => $this->error->description(),
                'code' => $this->error->value,
            ],
            $this->code
        );
    }
}
