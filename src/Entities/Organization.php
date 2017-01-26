<?php

namespace MoySklad\Entities;

use MoySklad\Traits\CreatesSimply;

class Organization extends AbstractEntity{
    use CreatesSimply;

    public static
        $entityName = 'organization';
}