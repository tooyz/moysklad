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

 class AbstractOrder extends AbstractEntity
{
    public static $entityName = '_a_order';

    public function create(Counterparty $counterparty = null, Organization $organization = null, EntityList $positions = null, CreationSpecs $specs = null){
        if ( empty($specs) ) $specs = CreationSpecs::create();
        $this->links->link( $counterparty, LinkingSpecs::create([
            'name' => 'agent',
        ]));
        $this->links->link( $organization );
        if ( $positions ){
            $positions->each(function(AbstractEntity $position){
                $position->assortment = [
                    'meta' => $position->getMeta()
                ];
                $this->links->link($position, LinkingSpecs::create([
                    'multiple' => true,
                    'name' => "positions",
                    'fields' => [
                        "accountId", "discount", "vat", "shipped", "reserve", "assortment", "quantity", "price"
                    ]
                ]));
            });
        }
        return $this->runCreateIfNotBatch($specs);
    }
}