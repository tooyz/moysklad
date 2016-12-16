<?php

namespace MoySklad\Components\Fields;

use MoySklad\MoySklad;

class EntityRelation extends AbstractFieldAccessor {
    public static function setupRelations(MoySklad $sklad, EntityFields &$entityFields){
        $fields = $entityFields->getInternal();
        foreach ($fields as $k=>$v){
            if ( is_array($v) ){
                array_walk($v, function($e, $i) use($k, $v, $entityFields, $sklad){
                    if ( $i === 'meta' ){
                        $class = MetaField::getClassFromPlainMeta($e);
                        $entityFields[$k] = new $class($sklad, $v);
                    }
                });
            }
        }
    }
}