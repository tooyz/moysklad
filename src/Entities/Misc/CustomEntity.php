<?php

namespace MoySklad\Entities\Misc;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Traits\RequiresOnlyNameForCreation;

class CustomEntity extends AbstractEntity  {
    use RequiresOnlyNameForCreation;
    public static $entityName = 'customentity';
}