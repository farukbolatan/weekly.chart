<?php

namespace App\Library\Exceptions;

use Throwable;

class CurlException extends \Exception
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}