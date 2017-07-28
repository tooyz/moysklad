<?php

namespace MoySklad\Entities;

use MoySklad\Traits\RequiresOnlyNameForCreation;

class Country extends AbstractEntity{
    use RequiresOnlyNameForCreation;
    public static $entityName = 'country';
}
