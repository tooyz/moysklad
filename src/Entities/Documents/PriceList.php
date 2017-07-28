<?php

namespace MoySklad\Entities\Documents;

use MoySklad\Entities\Organization;

class PriceList extends AbstractDocument{
    public static $entityName = 'pricelist';
    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, 'columns'];
    }
}
