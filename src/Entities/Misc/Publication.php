<?php

namespace MoySklad\Entities\Misc;

use MoySklad\Entities\AbstractEntity;

class Publication extends AbstractEntity  {
    public static $entityName = 'publication';

    public static function getFieldsRequiredForCreation()
    {
        return ["template"];
    }
}
