<?php

namespace MoySklad\Entities\Misc;

use MoySklad\Entities\AbstractEntity;

class Webhook extends AbstractEntity {

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

    public function create($url, $action, $entity)
    {
        $this->fields->url = $url;
        $this->fields->action = $action;
        $this->fields->entityType = $entity;
        return $this;
    }
}