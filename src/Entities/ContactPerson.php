<?php

namespace MoySklad\Entities;

use MoySklad\Traits\CreatesSimply;

class ContactPerson extends AbstractEntity{
    use CreatesSimply;

    public static
        $entityName = 'contactperson';
}