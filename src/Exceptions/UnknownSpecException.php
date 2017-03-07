<?php

namespace MoySklad\Exceptions;

use \Exception;

/**
 * Invalid key was passed in "create" method of Spec class
 * Class UnknownSpecException
 * @package MoySklad\Exceptions
 */
class UnknownSpecException extends Exception{
    public function __construct($spec = '', $code = 0, Exception $previous = null)
    {
        parent::__construct(
            'Unknown spec "'.$spec.'"',
            $code,
            $previous);
    }
}