<?php

namespace MoySklad\Entities;

use MoySklad\Traits\HasPlainCreation;

class Country extends AbstractEntity{
    use HasPlainCreation;

    public static
        $entityName = 'country';
}