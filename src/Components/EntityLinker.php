<?php

namespace MoySklad\Components;

use MoySklad\Components\Specs\ConstructionSpecs;
use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Entities\Counterparty;
use MoySklad\Entities\AbstractEntity;

//TODO: Стоит унаследовать от AbstractFieldAccessor
class EntityLinker{
    private
        $buckets = [];

    public function link(AbstractEntity $entity, LinkingSpecs $specs = null ){
        if ( !$specs ) $specs = LinkingSpecs::create();
        $name = $specs->name;
        $multiple = $specs->multiple;
        $selectedFields = $specs->fields;
        $excludedFields = $specs->excludedFields;

        if ( $selectedFields && $excludedFields ){
            throw new \Exception('Can\'t use "fields" param with "excludedFields"');
        }

        $cls = get_class($entity);
        if ( $selectedFields || $excludedFields ){
            $tFields = [];
            foreach ($entity->fields->getInternal() as $k=> $v){
                if ( $selectedFields ){
                    if ( in_array($k, $selectedFields) ){
                        $tFields[$k] = $v;
                    }
                } else {
                    if ( !in_array($k, $excludedFields) ){
                        $tFields[$k] = $v;
                    }
                }
            }
            $skladInstance = $entity->getSkladInstance();
            $newEntity = new $cls($skladInstance, $tFields, ConstructionSpecs::create([
                "relations" => false
            ]));
        } else {
            $newEntity = clone $entity;
        }
        if ( $name === null ){
            $name = $cls::$entityName;
        }
        if ( $multiple ){
            if ( empty($this->buckets[$name]) ) $this->buckets[$name] = [];
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

    public function reattachLinks(EntityLinker $otherLinker){
        $this->buckets = $otherLinker->getLinks();
    }
}