<?php

namespace MoySklad\Entities;

use MoySklad\Traits\CreatesSimply;

class Group extends AbstractEntity{
    use CreatesSimply;

    public static
        $entityName = 'group';
}