<?php

namespace MoySklad\Entities\Documents\Cash;

use MoySklad\Entities\Organization;

class CashIn extends AbstractCash{
    public static $entityName = 'cashin';
    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName,  'agent'];
    }
}