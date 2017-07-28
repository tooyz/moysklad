<?php

namespace MoySklad\Entities\Documents\Returns;

use MoySklad\Entities\Documents\Movements\Demand;
use MoySklad\Entities\Organization;
use MoySklad\Entities\Store;

class SalesReturn extends AbstractReturn{
    public static $entityName = 'salesreturn';
    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, Store::$entityName, Demand::$entityName, 'agent'];
    }
}
