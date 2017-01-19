<?php

namespace MoySklad\Entities\Orders;

use MoySklad\Components\MassRequest;
use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Entities\Assortment;
use MoySklad\Entities\Counterparty;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Organization;
use MoySklad\Lists\EntityList;
use MoySklad\Traits\DoesCreation;

 class AbstractOrder extends AbstractEntity
{
    use DoesCreation;
    public static $entityName = '_a_order';

    public function setCreate(Counterparty $counterparty = null, Organization $organization = null, $positions = null, CreationSpecs $specs = null){
        if ( empty($specs) ) $specs = CreationSpecs::create();
        $positions = new EntityList($this->skladInstance, $positions);
        $this->links->link( $counterparty, LinkingSpecs::create([
            'name' => 'agent',
        ]));
        $this->links->link( $organization );
        $positions->each(function(AbstractEntity $position){
            $position->assortment = [
                'meta' => $position->getMeta()
            ];
            $this->links->link($position, LinkingSpecs::create([
                'multiple' => true,
                'name' => "positions",
                'fields' => [
                    "assortment", "quantity", "price"
                ]
            ]));
        });
        return $this;
    }
}