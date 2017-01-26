<?php

namespace MoySklad\Entities\Folders;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Traits\CreatesSimply;

class AbstractFolder extends AbstractEntity
{
    use CreatesSimply;
    public static $entityName = '_a_folder';
}