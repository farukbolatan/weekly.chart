<?php

namespace App\Library\Exceptions;

use Throwable;

class NotFoundException extends \Exception
{
    const EXCEPTION_CODE = '900';

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $this->setCode();
        parent::__construct($message, $code, $previous);
    }

    public function setCode(): void
    {
        $this->code = self::EXCEPTION_CODE;
    }
}