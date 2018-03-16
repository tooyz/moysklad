<?php

namespace MoySklad\Entities\Documents\Movements;

use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\Entities\Organization;
use MoySklad\Entities\Store;

class AbstractMovement extends AbstractDocument{
    public static $entityName = "a_movement";
    public static function getFieldsRequiredForCreation()
    {
        return ['name', 'agent', Organization::$entityName, Store::$entityName];
    }
}
