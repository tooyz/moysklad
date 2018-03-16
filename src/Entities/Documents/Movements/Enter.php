<?php

namespace MoySklad\Entities\Documents\Movements;

use MoySklad\Entities\Organization;
use MoySklad\Entities\Store;

class Enter extends AbstractMovement {
    public static $entityName = 'enter';
    public static function getFieldsRequiredForCreation()
    {
        return ['name', Organization::$entityName, Store::$entityName];
    }
}
