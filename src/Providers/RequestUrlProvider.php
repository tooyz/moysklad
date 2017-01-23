<?php

namespace MoySklad\Providers;

use MoySklad\Utils\AbstractSingleton;

class RequestUrlProvider extends AbstractSingleton {
    protected static $instance = null;

    public function getCreateUrl($entityName){
        return 'entity/' . $entityName;
    }

    public function getUpdateUrl($entityName, $id){
        return $this->getByIdUrl($entityName, $id);
    }

    public function getDeleteUrl($entityName, $id){
        return $this->getByIdUrl($entityName, $id);
    }

    public function getByIdUrl($entityName, $id){
        return 'entity/' . $entityName . '/' . $id;
    }

    public function getListUrl($entityName){
        return "entity/" . $entityName;
    }

    public function relationListUrl($entityName, $entityId, $relatedEntityName){
        return $this->getListUrl($entityName) . "/" . $entityId . "/" . $relatedEntityName;
    }
}