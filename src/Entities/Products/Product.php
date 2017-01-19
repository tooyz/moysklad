<?php

namespace MoySklad\Entities\Products;

use MoySklad\Traits\DoesCreation;

class Product extends AbstractProduct {

    use DoesCreation;

    public static
        $entityName = 'product';
}