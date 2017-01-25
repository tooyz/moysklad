<?php

namespace MoySklad\Entities\Positions;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Traits\HasPlainCreation;

class AbstractPosition extends AbstractEntity{
    use HasPlainCreation;
    public static $entityName = 'a_position';
}