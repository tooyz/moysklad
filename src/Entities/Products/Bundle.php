<?php

namespace MoySklad\Entities\Products;

use MoySklad\Traits\RequiresOnlyNameForCreation;

class Bundle extends AbstractProduct{
    use RequiresOnlyNameForCreation;
    public static
        $entityName = 'bundle';
}
