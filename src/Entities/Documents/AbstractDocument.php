<?php

namespace MoySklad\Entities\Documents;

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
}