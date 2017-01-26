<?php

namespace MoySklad\Traits;

use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Entities\Counterparty;
use MoySklad\Entities\Organization;
use MoySklad\Lists\EntityList;

trait CreatesWithOrgAgentPositions{
    use LinksPositionsForCreation;
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