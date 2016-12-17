<?php

namespace MoySklad\Components\Fields;

use MoySklad\Entities\AbstractEntity;
use MoySklad\MoySklad;

class EntityRelation extends AbstractFieldAccessor {
    public static function setupRelations(MoySklad $sklad, AbstractEntity &$entity){
        $entityFields = $entity->fields;
        $intFields = $entity->fields->getInternal();
        $foundRelations = [];
        foreach ($intFields as $k=>$v){
            if ( is_array($v) || is_object($v) ){
                $ar = (array)$v;
                array_walk($ar, function($e, $i) use($k, $ar, $entityFields, $foundRelations, $sklad){
                    if ( $i === 'meta' ){
                        $class = MetaField::getClassFromPlainMeta($e);
                        $entityFields->{$k} = new $class($sklad, $ar);
                        $foundRelations[$k] = &$entityFields->{$k};
                    }
                });
            }
        }
        $entity->setRelations($foundRelations);
    }

    public function hideRelations(AbstractEntity $entity){
        foreach ($this as $relName => $rel){
            unset($entity->{$relName});
        }
    }

    public function restoreRelations(AbstractEntity $entity){
        foreach ($this as $relName => $rel){
            $entity->{$relName} = $rel;
        }
    }
}