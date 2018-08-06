<?php

namespace MoySklad\Entities\Documents\PriceLists;

use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\Entities\Organization;

class PriceList extends AbstractDocument {
    public static $entityName = 'pricelist';
    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, 'columns'];
    }
}
