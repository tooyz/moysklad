<?php

namespace MoySklad\Exceptions;

use \Exception;

class ResponseParseException extends Exception{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            "Response decode error: " . $message,
            $code,
            $previous);
    }
}