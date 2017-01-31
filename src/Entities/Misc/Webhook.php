<?php

namespace MoySklad\Entities\Misc;

use MoySklad\Entities\AbstractEntity;

class Webhook extends AbstractEntity {

    const
        ACTION_CREATE = 'CREATE',
        ACTION_UPDATE = 'UPDATE',
        ACTION_DELETE = 'DELETE';

    public static $entityName = 'webhook';

    public static function getFieldsRequiredForCreation()
    {
        return ['url', 'action', 'entityType'];
    }

    public function disable(){
        $this->fields->enabled = false;
        return $this->update();
    }

    public function enable(){
        $this->fields->enabled = true;
        return $this->update();
    }
}