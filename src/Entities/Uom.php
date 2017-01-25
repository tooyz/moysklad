<?php

namespace MoySklad\Entities;

use MoySklad\Traits\HasPlainCreation;

class Uom extends AbstractEntity{
    use HasPlainCreation;

    public static
        $entityName = 'uom';
}