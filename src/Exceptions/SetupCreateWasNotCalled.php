<?php

namespace MoySklad\Exceptions;

use \Exception;
use MoySklad\Entities\AbstractEntity;

class SetupCreateWasNotCalled extends Exception{
    public function __construct($entity, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            get_class($entity) . ': "setupCreate()" method must be called before "runCreate()"',
            $code,
            $previous);
    }
}