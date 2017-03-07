<?php

namespace MoySklad\Entities\Products;

use MoySklad\Entities\Assortment;

class Consignment extends AbstractProduct{
    public static
        $entityName = 'consignment';

    public static function getFieldsRequiredForCreation()
    {
        return ["label", Assortment::class];
    }
}