<?php

namespace MoySklad\Exceptions;

use \Exception;
use MoySklad\Entities\AbstractEntity;

class IncompleteCreationFieldsException extends Exception{
    public function __construct(AbstractEntity $entity, $code = 0, Exception $previous = null)
    {
        $c = get_class($entity);
        $requiredFields = $c::getFieldsRequiredForCreation();
        $failedFields = [];
        foreach ( $requiredFields as $requiredField ){
            if ( !isset($entity->{$requiredField}) ){
                $failedFields[] = $requiredField;
            }
        }
        parent::__construct(
            "Entity " . $c . " requires these fields to be created: " . \json_encode($requiredFields) .", has no these fields at the moment: " . \json_encode($failedFields) ,
            $code,
            $previous);
    }
}