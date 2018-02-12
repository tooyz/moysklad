<?php

namespace MoySklad\Exceptions;

use \Exception;

class PosTokenException extends Exception{
    public function __construct( $code = 0, Exception $previous = null)
    {
        parent::__construct(
            "POS token is used, but it's invalid or empty",
            $code,
            $previous);
    }
}
