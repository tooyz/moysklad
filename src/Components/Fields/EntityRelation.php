<?php

namespace MoySklad\Components\Fields;

use MoySklad\Entities\AbstractEntity;
use MoySklad\MoySklad;

class EntityRelation extends AbstractFieldAccessor {
    public static function createRelations(MoySklad $sklad, AbstractEntity &$entity){
        $internalFields = $entity->fields->getInternal();
        $foundRelations = [];
        foreach ($internalFields as $k=>$v){
            if ( is_array($v) || is_object($v) ){
                $ar = (array)$v;
                array_walk($ar, function($e, $i) use($k, $ar, &$foundRelations, $sklad){
                    if ( $i === 'meta' ){
                        $class = MetaField::getClassFromPlainMeta($e);
                        //TODO: тут возможно списочная мета, типо https://online.moysklad.ru/api/remap/1.1/entity/customerorder/.../documents
                        //TODO: надо создать что то типо meta_list
                        if ( $class ){
                            $foundRelations[$k] = new $class($sklad, $ar);
                        }
                    }
                });
            }
        }
        return new static($foundRelations);
    }

}