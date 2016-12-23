<?php

namespace MoySklad\Entities\Misc;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Traits\DoesCreation;

class Webhook extends AbstractEntity {
    use DoesCreation;

    const
        ACTION_CREATE = 'CREATE',
        ACTION_UPDATE = 'UPDATE',
        ACTION_DELETE = 'DELETE';

    public static $entityName = 'webhook';

    public function disable(){
        $this->fields->enabled = false;
        return $this->update();
    }

    public function enable(){
        $this->fields->enabled = true;
        return $this->update();
    }

    public function setCreate($url, $action, $entity)
    {
        $this->fields->url = $url;
        $this->fields->action = $action;
        $this->fields->entity = $entity;
    }
}