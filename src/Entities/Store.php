<?php

namespace MoySklad\Entities;

use MoySklad\Traits\CreatesSimply;

class Store extends AbstractEntity{
    use CreatesSimply;

    public static
        $entityName = 'store';
}