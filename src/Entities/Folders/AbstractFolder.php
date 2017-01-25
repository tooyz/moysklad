<?php

namespace MoySklad\Entities\Folders;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Traits\HasPlainCreation;

class AbstractFolder extends AbstractEntity
{
    use HasPlainCreation;
    public static $entityName = '_a_folder';
}