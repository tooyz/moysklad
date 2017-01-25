<?php

namespace MoySklad\Entities;

use MoySklad\Traits\HasPlainCreation;

class Group extends AbstractEntity{
    use HasPlainCreation;

    public static
        $entityName = 'group';
}