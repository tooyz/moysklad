<?php

namespace MoySklad\Entities;

use MoySklad\Traits\RequiresOnlyNameForCreation;

class Project extends AbstractEntity{
    use RequiresOnlyNameForCreation;
    public static $entityName = 'project';
}