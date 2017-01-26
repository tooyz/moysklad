<?php

namespace MoySklad\Entities\Documents\Orders;

use MoySklad\Components\MassRequest;
use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Entities\Assortment;
use MoySklad\Entities\Counterparty;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\Entities\Organization;
use MoySklad\Lists\EntityList;
use MoySklad\Traits\LinksPositionsForCreation;

class AbstractOrder extends AbstractDocument{
    use LinksPositionsForCreation;
    public static $entityName = '_a_order';

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