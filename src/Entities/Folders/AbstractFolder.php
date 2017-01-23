<?php

namespace MoySklad\Entities\Folders;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Traits\DoesCreation;

class AbstractFolder extends AbstractEntity
{
    use DoesCreation;

    public static $entityName = '_a_folder';
}