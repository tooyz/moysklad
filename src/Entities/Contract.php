<?php

namespace MoySklad\Entities;

use MoySklad\Traits\CreatesSimply;

class Contract extends AbstractEntity{
    use CreatesSimply;

    public static
        $entityName = 'contract';
}