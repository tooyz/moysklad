<?php

namespace MoySklad\Exceptions;

use \Exception;

/**
 * Url should pass FILTER_VALIDATE_URL filter
 * Class InvalidUrlException
 * @package MoySklad\Exceptions
 */
class InvalidUrlException extends Exception{
    public function __construct($url, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            "Url $url is invalid",
            $code,
            $previous);
    }
}
