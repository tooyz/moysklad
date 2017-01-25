<?php

namespace MoySklad\Entities;

use MoySklad\Traits\HasPlainCreation;

class ContactPerson extends AbstractEntity{
    use HasPlainCreation;

    public static
        $entityName = 'contactperson';
}