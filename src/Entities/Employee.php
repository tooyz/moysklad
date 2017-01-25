<?php

namespace MoySklad\Entities;

use MoySklad\Traits\HasPlainCreation;

class Employee extends AbstractEntity{
    use HasPlainCreation;

    public static
        $entityName = 'employee';
}