<?php

namespace MoySklad\Entities\Folders;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Traits\RequiresOnlyNameForCreation;

class AbstractFolder extends AbstractEntity
{
    use RequiresOnlyNameForCreation;
    public static $entityName = '_a_folder';
}