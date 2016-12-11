<?php

namespace MoySklad\Entities\Orders;

use MoySklad\Components\MassRequest;
use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Entities\Assortment;
use MoySklad\Entities\Counterparty;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Organization;
use MoySklad\Interfaces\ICreatable;

abstract class AbstractOrder extends AbstractEntity implements ICreatable
{
    public static $entityName = '_a_order';

    public function setCreate(Counterparty $counterparty = null, Organization $organization = null, $positions = [], CreationSpecs $specs = null){
        if ( empty($specs) ) $specs = new CreationSpecs();
        $this->links->link( $counterparty, new LinkingSpecs([
            'name' => 'agent',
        ]));
        $this->links->link( $organization );
        foreach ($positions as $position ){
            $position->assortment = [
                'meta' => $position->getMeta()
            ];
            $this->links->link($position, new LinkingSpecs([
                'multiple' => true,
                'name' => "positions",
                'fields' => [
                    "assortment", "quantity", "price"
                ]
            ]));
        }
        return $this;
    }

    public function doCreate()
    {
        $mr = new MassRequest($this->getSkladInstance(), $this);
        return $mr->create()[0];
    }
}