<?php

namespace MoySklad\Entities\Documents;

use MoySklad\Entities\Organization;
use MoySklad\Entities\Store;

class Inventory extends AbstractDocument{
    public static $entityName = 'inventory';
    public static function getFieldsRequiredForCreation()
    {
        return [ Organization::$entityName, Store::$entityName];
    }
}
