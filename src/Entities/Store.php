<?php

namespace MoySklad\Entities;

use MoySklad\Traits\RequiresOnlyNameForCreation;

class Store extends AbstractEntity{
    use RequiresOnlyNameForCreation;
    public static $entityName = 'store';
}
