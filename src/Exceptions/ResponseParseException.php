<?php

namespace MoySklad\Exceptions;

use \Exception;

/**
 * Response could not be json_decoded
 * Class ResponseParseException
 * @package MoySklad\Exceptions
 */
class ResponseParseException extends Exception{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            "Response decode error: " . $message,
            $code,
            $previous);
    }
}