<?php

namespace MoySklad\Entities;

use MoySklad\Traits\HasPlainCreation;

class Account extends AbstractEntity{
    use HasPlainCreation;

    public static
        $entityName = 'account';
}