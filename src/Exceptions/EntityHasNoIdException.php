<?php

namespace MoySklad\Exceptions;

use \Exception;
use MoySklad\Entities\AbstractEntity;

/**
 * Entity has no "id" field
 * Class EntityHasNoIdException
 * @package MoySklad\Exceptions
 */
class EntityHasNoIdException extends Exception{
    public function __construct(AbstractEntity $entity, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            "Entity " . get_class($entity) . " has no id",
            $code,
            $previous);
    }
}
