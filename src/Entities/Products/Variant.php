<?php

namespace MoySklad\Entities\Products;

use MoySklad\Entities\Misc\Characteristics;

class Variant extends AbstractProduct{
    public static $entityName = 'variant';

    public static function getFieldsRequiredForCreation()
    {
        return [Product::class, Characteristics::class];
    }
}