<?php

namespace MoySklad\Entities;

use MoySklad\Traits\DoesCreation;

class Currency extends AbstractEntity{
    use DoesCreation;
    public static
        $entityName = 'currency';
}