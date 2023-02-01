<?php

namespace App\Exceptions;

use Exception;
use App\Utilities\Helpers;
use App\Models\Transaction;

class FailedTransactionException extends Exception
{
    public function __construct(Transaction $transaction, int $code = 403)
    {
        $this->message = $transaction->description;
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
