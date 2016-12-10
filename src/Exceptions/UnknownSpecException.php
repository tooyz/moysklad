<?php

namespace MoySklad\Exceptions;

use \Exception;

class UnknownSpecException extends Exception{
    public function __construct($spec = '', $code = 0, Exception $previous = null)
    {
        parent::__construct(
            "Unknown spec {'$spec'}",
            $code,
            $previous);
    }
}