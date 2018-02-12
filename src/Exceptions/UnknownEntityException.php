<?php

namespace MoySklad\Exceptions;

use \Exception;

/**
 * No class with given "type" was found in EntityRegistry
 * Class UnknownEntityException
 * @package MoySklad\Exceptions
 */
class UnknownEntityException extends Exception{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            "Unknown moysklad entity: " . $message,
            $code,
            $previous);
    }
}
