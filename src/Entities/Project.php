<?php

namespace MoySklad\Entities;

use MoySklad\Traits\CreatesSimply;

class Project extends AbstractEntity{
    use CreatesSimply;

    public static
        $entityName = 'project';
}