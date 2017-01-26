<?php

namespace MoySklad\Entities;

use MoySklad\Traits\CreatesSimply;

class Country extends AbstractEntity{
    use CreatesSimply;

    public static
        $entityName = 'country';
}