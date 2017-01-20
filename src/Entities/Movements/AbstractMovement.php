<?php

namespace MoySklad\Entities\Movements;

use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Organization;
use MoySklad\Entities\Products\AbstractProduct;
use MoySklad\Entities\Store;
use MoySklad\Lists\EntityList;
use MoySklad\Traits\DoesCreation;

class AbstractMovement extends AbstractEntity{
    use DoesCreation;
    public static $entityName = "a_movement";

    public function setupCreate(Organization $organization, Store $store, $positions = null, CreationSpecs $specs = null){
        if ( empty($specs) ) $specs = CreationSpecs::create();
        $this->links->link($organization);
        $this->links->link($store);
        if ( $positions ){
            $positions = new EntityList($this->skladInstance, $positions);
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
        $this->setupCreateWasCalled = true;
        return $this;
    }
}