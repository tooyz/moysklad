<?php

namespace MoySklad\Entities\Misc;

use MoySklad\Entities\AbstractEntity;

class Webhook extends AbstractEntity {

    const
        ACTION_CREATE = 'CREATE',
        ACTION_UPDATE = 'UPDATE',
        ACTION_DELETE = 'DELETE';

    public static $entityName = 'webhook';

    /**
     * @return array
     */
    public static function getFieldsRequiredForCreation()
    {
        return ['url', 'action', 'entityType'];
    }

    /**
     * @return AbstractEntity
     */
    public function disable(){
        $this->fields->enabled = false;
        return $this->update();
    }

    /**
     * @return AbstractEntity
     */
    public function enable(){
        $this->fields->enabled = true;
        return $this->update();
    }
}
