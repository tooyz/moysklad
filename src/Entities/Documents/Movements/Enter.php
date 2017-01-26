<?php

namespace MoySklad\Entities\Documents\Movements;


use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Entities\Documents\Movements\Base\AbstractMovement;
use MoySklad\Entities\Organization;
use MoySklad\Entities\Store;
use MoySklad\Lists\EntityList;
use MoySklad\Traits\LinksPositionsForCreation;

class Enter extends AbstractMovement  {
    use LinksPositionsForCreation;

    public static $entityName = 'enter';

    public function create(Organization $organization, Store $store, EntityList $positions = null, CreationSpecs $specs = null){
        if ( empty($specs) ) $specs = CreationSpecs::create();
        $this->links->link($organization);
        $this->links->link($store);
        $this->linkPositionsForCreation($positions);
        return $this->runCreateIfNotBatch($specs);
    }
}