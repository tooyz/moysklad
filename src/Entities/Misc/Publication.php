<?php

namespace MoySklad\Entities\Misc;

use MoySklad\Entities\AbstractEntity;

class Publication extends AbstractEntity  {
    public static $entityName = 'operationpublication';

    public static function getFieldsRequiredForCreation()
    {
        return ["template"];
    }
}
