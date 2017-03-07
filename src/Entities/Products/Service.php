<?php

namespace MoySklad\Entities\Products;

use MoySklad\Entities\Products\AbstractProduct;
use MoySklad\Traits\RequiresOnlyNameForCreation;

class Service extends AbstractProduct {
    use RequiresOnlyNameForCreation;
    public static $entityName = 'service';
}