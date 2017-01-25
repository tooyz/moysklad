<?php

namespace MoySklad\Entities\Movements;

use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Organization;
use MoySklad\Entities\Products\AbstractProduct;
use MoySklad\Entities\Store;
use MoySklad\Lists\EntityList;

class AbstractMovement extends AbstractEntity{
    public static $entityName = "a_movement";

    public function create(Organization $organization, Store $store, $positions = null, CreationSpecs $specs = null){
        if ( empty($specs) ) $specs = CreationSpecs::create();
        $this->links->link($organization);
        $this->links->link($store);
        if ( $positions ){
            $positions = new EntityList($this->getSkladInstance(), $positions);
            $positions->each(function(AbstractProduct $position){
                $position->assortment = [
                    'meta' => $position->getMeta()
                ];
                $this->links->link($position, LinkingSpecs::create([
                    'multiple' => true,
                    'name' => "positions",
                    'fields' => [
                        "assortment", "quantity", "price", "overhead", "reason", "accountId", "gdt", "country", "things"
                    ]
                ]));
            });
        }
        return $this->runCreateIfNotBatch($specs);
    }
}