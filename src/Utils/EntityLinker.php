<?php

namespace MoySklad\Utils;

use MoySklad\Entities\Counterparty;
use MoySklad\Entities\AbstractEntity;

class EntityLinker{
    private
        $buckets = [];

    public function link(AbstractEntity $entity, $options = [] ){
        $name = $options['name'];
        $multiple = $options['multiple'];
        $selectedFields = $options['fields'];

        $cls = get_class($entity);
        if ( $selectedFields ){
            $tFields = [];
            foreach ($entity->fields->getInternal() as $k=> $v){
                if ( in_array($k, $selectedFields) ){
                    $tFields[$k] = $v;
                }
            }
            $newEntity = new $cls($entity->getSkladInstance(), $tFields);
        } else {
            $newEntity = clone $entity;
        }
        if ( $name === null ){
            $name = $cls::$entityName;
        }
        if ( $multiple && empty($this->buckets[$name]) ) $this->buckets[$name] = [];
        if ( $multiple ){
            $this->buckets[$name][] = $newEntity;
        } else {
            $this->buckets[$name] = $newEntity;
        }
    }

    public function linkMany($entities){
        foreach ($entities as $entity){

        }
    }

    public function getLinks(){
        return $this->buckets;
    }
}