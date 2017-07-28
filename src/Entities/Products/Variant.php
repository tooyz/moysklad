<?php

namespace MoySklad\Entities\Products;

use MoySklad\Entities\Misc\Characteristics;

class Variant extends AbstractProduct{
    public static $entityName = 'variant';

    public static function getFieldsRequiredForCreation()
    {
        return [Product::$entityName, Characteristics::$entityName];
    }
}