<?php

namespace MoySklad\Entities\Documents\Movements;

use MoySklad\Entities\Organization;
use MoySklad\Entities\Store;

class Demand extends AbstractMovement {
    public static $entityName = 'demand';

    public static function getFieldsRequiredForCreation()
    {
        return ['agent', Organization::$entityName, Store::$entityName];
    }
}
