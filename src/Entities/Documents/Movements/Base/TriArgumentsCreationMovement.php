<?php

namespace MoySklad\Entities\Documents\Movements\Base;

use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Entities\Counterparty;
use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\Entities\Organization;
use MoySklad\Entities\Products\AbstractProduct;
use MoySklad\Entities\Store;
use MoySklad\Lists\EntityList;
use MoySklad\Traits\LinksPositionsForCreation;

abstract class TriArgumentsCreationMovement extends AbstractMovement{
    use LinksPositionsForCreation;
    public function create(
        Counterparty $counterparty = null,
        Organization $organization = null,
        Store $store,
        EntityList $positions = null,
        CreationSpecs $specs = null
    ){
        if ( empty($specs) ) $specs = CreationSpecs::create();
        $this->links->link( $counterparty, LinkingSpecs::create([
            'name' => 'agent',
        ]));
        $this->links->link($store);
        $this->links->link( $organization );
        $this->linkPositionsForCreation($positions);
        return $this->runCreateIfNotBatch($specs);
    }
}