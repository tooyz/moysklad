<?php

namespace MoySklad\Entities\Documents\Movements;

use MoySklad\Entities\Organization;

class Move extends AbstractMovement {
    public static $entityName = 'move';
    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName, 'targetStore', 'sourceStore'];
    }
}
