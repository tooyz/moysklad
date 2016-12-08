<?php

namespace MoySklad\Entities\Orders;

use MoySklad\Entities\Counterparty;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Organization;

class AbstractOrder extends AbstractEntity
{
    public static $entityName = 'order';

    public function create(Counterparty $counterparty, Organization $organization, $params = [])
    {
        $this->links->link( $counterparty, [
            'name' => 'agent',
        ]);
        $this->links->link( $organization );
        return parent::_create();
    }
}