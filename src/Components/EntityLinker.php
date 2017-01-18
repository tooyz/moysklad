<?php

namespace MoySklad\Components;

use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Entities\Counterparty;
use MoySklad\Entities\AbstractEntity;

class EntityLinker{
    private
        $buckets = [];

    public function link(AbstractEntity $entity, LinkingSpecs $specs = null ){
        if ( !$specs ) $specs = LinkingSpecs::create();
        $name = $specs->name;
        $multiple = $specs->multiple;
        $selectedFields = $specs->fields;

        $cls = get_class($entity);
        if ( $selectedFields ){
            $tFields = [];
            foreach ($entity->fields->getInternal() as $k=> $v){
                if ( in_array($k, $selectedFields) ){
                    $tFields[$k] = $v;
                }
            }
            $skladInstance = $entity->getSkladInstance();
            $newEntity = new $cls($skladInstance, $tFields, CreationSpecs::create([
                "relations" => false
            ]));
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

    public function reattachLinks(EntityLinker $otherLinker){
        $this->buckets = $otherLinker->getLinks();
    }
}