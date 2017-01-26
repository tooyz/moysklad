<?php

namespace MoySklad\Entities;

use MoySklad\Traits\CreatesSimply;

class Uom extends AbstractEntity{
    use CreatesSimply;

    public static
        $entityName = 'uom';
}