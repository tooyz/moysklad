<?php

namespace MoySklad\Entities\Movements;

use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Products\AbstractProduct;
use MoySklad\Lists\EntityList;
use MoySklad\Traits\DoesCreation;

class AbstractMovement extends AbstractEntity{
    use DoesCreation;
    public static $entityName = "a_movement";

    public function setCreate($positions = null){
        if ( empty($specs) ) $specs = CreationSpecs::create();
        $positions = new EntityList($this->skladInstance, $positions);
        $positions->each(function(AbstractProduct $position){
           $this->links->link($position, LinkingSpecs::create([
               'multiple' => true,
               'name' => "positions",
               'fields' => [
                   "assortment", "quantity", "price", "overhead", "reason", "id", "accountId", "gdt", "country", "things"
               ]
           ]));
        });
        return $this;
    }
}