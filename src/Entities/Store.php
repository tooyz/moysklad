<?php

namespace MoySklad\Entities;

use MoySklad\Traits\HasPlainCreation;

class Store extends AbstractEntity{
    use HasPlainCreation;

    public static
        $entityName = 'store';
}