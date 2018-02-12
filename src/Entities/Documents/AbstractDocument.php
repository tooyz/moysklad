<?php

namespace MoySklad\Entities\Documents;

use MoySklad\Components\Http\RequestConfig;
use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Misc\Attribute;
use MoySklad\Entities\Misc\Publication;
use MoySklad\Lists\EntityList;
use MoySklad\MoySklad;
use MoySklad\Registers\ApiUrlRegistry;

class AbstractDocument extends AbstractEntity{
    public static $entityName = 'a_document';

    /**
     * @param MoySklad $sklad
     * @param Attribute $attribute
     * @return \stdClass
     */
    public static function getAttributeMetaData(MoySklad $sklad, Attribute $attribute){
        return $sklad->getClient()->get(
            ApiUrlRegistry::instance()->getMetadataAttributeUrl(static::$entityName, $attribute->id)
        );
    }

    /**
     * Create document template
     * @deprecated $sklad argument is deprecated and will be removed
     * @param MoySklad $sklad
     * @param $makeEmptyTemplate
     * @return \stdClass
     */
    public function newTemplate(MoySklad $sklad, $makeEmptyTemplate = false){
        $requestConfig = new RequestConfig();
        if ( $makeEmptyTemplate ) {
            $requestConfig->set("ignoreRequestBody", true);
        }
        return $sklad->getClient()->put(
            ApiUrlRegistry::instance()->getNewDocumentTemplateUrl(static::$entityName),
            $this->mergeFieldsWithLinks(),
            $requestConfig
        );
    }

    public function getPublications(QuerySpecs $querySpecs){
        return Publication::query($this->getSkladInstance(), $querySpecs)
            ->setCustomQueryUrl(ApiUrlRegistry::instance()->getDocumentPublicationsUrl($this::$entityName, $this->findEntityId()))
            ->getList();
    }

    public function createPublication(Publication $publication){
        $publication->validateFieldsRequiredForCreation();
        $res = $this->getSkladInstance()->getClient()->post(
            ApiUrlRegistry::instance()->getDocumentPublicationsUrl(static::$entityName, $this->findEntityId()),
            $publication->mergeFieldsWithLinks()
        );
        return new Publication($this->getSkladInstance(), $res);
    }

    public function deletePublication(Publication $publication){
        $this->getSkladInstance()->getClient()->delete(
            ApiUrlRegistry::instance()->getDocumentPublicationWithIdUrl(static::$entityName, $this->findEntityId(), $publication->findEntityId())
        );
        return true;
    }

    public function getPublicationById($id){
        $res = $this->getSkladInstance()->getClient()->get(
            ApiUrlRegistry::instance()->getDocumentPublicationWithIdUrl(static::$entityName, $this->findEntityId(), $id)
        );
        return new Publication($this->getSkladInstance(), $res);
    }
}
