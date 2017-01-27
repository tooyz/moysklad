<?php

namespace MoySklad\Entities\Documents\Orders;

use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\Entities\Organization;

class AbstractOrder extends AbstractDocument{
    public static $entityName = '_a_order';
    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, 'agent'];
    }
}