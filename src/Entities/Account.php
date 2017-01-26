<?php

namespace MoySklad\Entities;

use MoySklad\Traits\CreatesSimply;

class Account extends AbstractEntity{
    use CreatesSimply;

    public static
        $entityName = 'account';
}