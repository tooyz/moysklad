<?php

namespace MoySklad\Entities\Documents\Returns;

use MoySklad\Entities\Documents\Movements\Supply;
use MoySklad\Entities\Organization;
use MoySklad\Entities\Store;

class PurchaseReturn extends AbstractReturn{
    public static $entityName = 'purchasereturn';
    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, Store::$entityName, Supply::$entityName, 'agent'];
    }
}
