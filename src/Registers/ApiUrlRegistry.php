<?php

namespace MoySklad\Registers;

use MoySklad\Entities\Reports\AbstractReport;
use MoySklad\Utils\AbstractSingleton;

class ApiUrlRegistry extends AbstractSingleton {
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

    public function getNewDocumentTemplateUrl($entityName){
        return 'entity/' . $entityName . '/new';
    }

    public function getPosAttachTokenUrl($retailStoreId){
        return "admin/attach/${retailStoreId}";
    }

    public function getDocumentPublicationsUrl($entityName, $id){
        return 'entity/' . $entityName . "/" . $id . '/publication';
    }

    public function getDocumentPublicationWithIdUrl($entityName, $id, $publicationId){
        return 'entity/' . $entityName . "/" . $id . '/publication/' . $publicationId;
    }
}
