<?php

namespace MoySklad\Exceptions;

use \Exception;

/**
 * Thrown when one field can't be used with another
 * Class IncompatibleFieldsException
 * @package MoySklad\Exceptions
 */
class IncompatibleFieldsException extends Exception{
    public function __construct($firstField, $secondField, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            'Field "' . $firstField . '" can\'t be used with "' . $secondField . '" field' ,
            $code,
            $previous);
    }
}