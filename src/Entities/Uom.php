<?php

namespace MoySklad\Entities;

use MoySklad\Traits\RequiresOnlyNameForCreation;

class Uom extends AbstractEntity{
    use RequiresOnlyNameForCreation;
    public static $entityName = 'uom';
}