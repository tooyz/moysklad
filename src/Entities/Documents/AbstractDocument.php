<?php

namespace MoySklad\Entities\Documents;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Misc\Attribute;
use MoySklad\MoySklad;
use MoySklad\Repositories\RequestUrlRepository;

class AbstractDocument extends AbstractEntity{
    public static $entityName = 'a_document';

    public static function getAttributeMetaData(MoySklad $sklad, Attribute $attribute){
        return $sklad->getClient()->get(
            RequestUrlRepository::instance()->getMetadataAttributeUrl(static::$entityName, $attribute->id)
        );
    }
}