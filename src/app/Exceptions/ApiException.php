<?php

namespace App\Exceptions;

use Exception;
use App\Utilities\Helpers;
use App\Utilities\Utilities;

class ApiException extends Exception
{
    public function __construct(string $message, int $code = 500)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function report(Exception $exception)
    {
    }

    public function render($exception)
    {
        return (new Helpers())->errorResponder(null,  $this->getCode(), $this->getMessage());
        // return new Exception($this->getMessage(), $this->getCode());
    }
}
