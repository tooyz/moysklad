<?php

namespace MoySklad\Entities\Documents\Movements;


use MoySklad\Entities\Organization;
use MoySklad\Entities\Store;

class Loss extends AbstractMovement {
    public static $entityName = 'loss';
    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, Store::$entityName];
    }
}
