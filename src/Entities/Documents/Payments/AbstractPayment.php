<?php

namespace MoySklad\Entities\Documents\Payments;

use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Entities\Counterparty;
use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\Entities\Organization;

class AbstractPayment extends AbstractDocument{
    public static $entityName = 'a_payment';

    public function create(Organization $organization, Counterparty $counterparty){
        $this->links->link($organization);
        $this->links->link($counterparty, LinkingSpecs::create([
            'name' => 'agent'
        ]));
    }
}