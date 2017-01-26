<?php

namespace MoySklad\Entities\Documents\Movements;

use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Entities\Counterparty;
use MoySklad\Entities\Documents\Movements\Base\AbstractMovement;
use MoySklad\Entities\Organization;
use MoySklad\Lists\EntityList;
use MoySklad\Traits\LinksPositionsForCreation;

class Loss extends AbstractMovement {
    use LinksPositionsForCreation;
    public static $entityName = 'loss';

    public function create(Counterparty $counterparty = null, Organization $organization = null, EntityList $positions = null, CreationSpecs $specs = null){
        if ( empty($specs) ) $specs = CreationSpecs::create();
        $this->links->link( $counterparty, LinkingSpecs::create([
            'name' => 'agent',
        ]));
        $this->links->link( $organization );
        $this->linkPositionsForCreation($positions);
        return $this->runCreateIfNotBatch($specs);
    }
}