<?php

namespace MoySklad\Exceptions;

use \Exception;

class StackingRequestDifferentMethodException extends Exception{
    public function __construct($firstMethod, $currentMethod,  $code = 0, Exception $previous = null)
    {
        parent::__construct(
            "Stacked request used with {$currentMethod}, expected {$firstMethod} call",
            $code,
            $previous);
    }
}