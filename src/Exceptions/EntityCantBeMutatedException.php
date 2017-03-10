<?php

namespace MoySklad\Exceptions;

use \Exception;
use MoySklad\Interfaces\PreventsMutation;

class EntityCantBeMutatedException extends Exception{
    public function __construct( PreventsMutation $entity, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            get_class($entity) . " can't be mutated",
            $code,
            $previous);
    }
}