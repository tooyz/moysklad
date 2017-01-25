<?php

namespace MoySklad\Entities;

use MoySklad\Traits\HasPlainCreation;

class Organization extends AbstractEntity{
    use HasPlainCreation;

    public static
        $entityName = 'organization';
}