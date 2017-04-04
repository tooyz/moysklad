<?php

namespace MoySklad\Entities\Documents;

use MoySklad\Components\Fields\MetaField;
use MoySklad\Components\Http\RequestConfig;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Misc\Attribute;
use MoySklad\MoySklad;
use MoySklad\Repositories\ApiUrlRepository;

class AbstractDocument extends AbstractEntity{
    public static $entityName = 'a_document';

    /**
     * @param MoySklad $sklad
     * @param Attribute $attribute
     * @return \stdClass
     */
    public static function getAttributeMetaData(MoySklad $sklad, Attribute $attribute){
        return $sklad->getClient()->get(
            ApiUrlRepository::instance()->getMetadataAttributeUrl(static::$entityName, $attribute->id)
        );
    }

    /**
     * Create document template
     * @param MoySklad $sklad
     * @param null $forEntity
     * @param MetaField|null $meta
     * @return \stdClass
     */
    public static function newTemplate(MoySklad $sklad, $forEntity = null, MetaField $meta = null){
        $requestConfig = new RequestConfig();
        if ( empty($forEntity) || empty($meta) ) {
            $requestConfig->set("ignoreRequestBody", true);
        }
        return $sklad->getClient()->put(
            ApiUrlRepository::instance()->getNewDocumentTemplateUrl(static::$entityName),
            [
                $forEntity => [
                    "meta" => $meta
                ]
            ],
            $requestConfig
        );
    }
}