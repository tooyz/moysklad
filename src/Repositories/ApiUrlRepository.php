<?php

namespace MoySklad\Repositories;

use MoySklad\Entities\Reports\AbstractReport;
use MoySklad\Utils\AbstractSingleton;

class ApiUrlRepository extends AbstractSingleton {
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

    public function getReportUrl($reportName){
        return AbstractReport::$entityName . '/'.$reportName;
    }

    public function getReportWithParamUrl($reportName, $param){
        return AbstractReport::$entityName . '/'.$reportName.'/' . $param;
    }

    public function getMetadataUrl($entityName){
        return 'entity/' . $entityName . '/metadata';
    }

    public function getMetadataAttributeUrl($entityName, $fieldId){
        return 'entity/' . $entityName . '/metadata/attributes/' . $fieldId;
    }

    public function getPosAttachTokenUrl($retailStoreId){
        return "admin/attach/${retailStoreId}";
    }
}