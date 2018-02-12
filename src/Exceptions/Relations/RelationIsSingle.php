<?php

namespace MoySklad\Exceptions\Relations;

use \Exception;

/**
 * Class RelationIsSingle
 * @package MoySklad\Exceptions\Relations
 */
class RelationIsSingle extends Exception{
    public function __construct($relationName, $entityClass, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            'Relation "' . $relationName .'" is single, but queried as list on ' . $entityClass,
            $code,
            $previous);
    }
}
