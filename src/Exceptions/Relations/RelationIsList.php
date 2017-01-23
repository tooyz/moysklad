<?php

namespace MoySklad\Exceptions\Relations;

use \Exception;

class RelationIsList extends Exception{
    public function __construct($relationName, $entityClass, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            'Relation "' . $relationName .'" is list, but queried as single on ' . $entityClass,
            $code,
            $previous);
    }
}