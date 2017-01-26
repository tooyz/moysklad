<?php

namespace MoySklad\Repositories;

use MoySklad\Utils\AbstractSingleton;

class RequestUrlRepository extends AbstractSingleton {
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

    public function getRelationListUrl($entityName, $entityId, $relatedEntityName){
        return $this->getListUrl($entityName) . "/" . $entityId . "/" . $relatedEntityName;
    }

    public function getReportUrl($period){
        return 'report/dashboard/' . $period;
    }

    public function getMetadataUrl($entityName){
        return 'entity/' . $entityName . '/metadata';
    }

    public function getMetadataAttributeUrl($entityName, $fieldId){
        return 'entity/' . $entityName . '/metadata/attributes/' . $fieldId;
    }
}