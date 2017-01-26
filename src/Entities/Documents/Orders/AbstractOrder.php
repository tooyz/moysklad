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
use MoySklad\Traits\CreatesWithOrgAgentPositions;
use MoySklad\Traits\LinksPositionsForCreation;

class AbstractOrder extends AbstractDocument{
    use CreatesWithOrgAgentPositions;
    public static $entityName = '_a_order';
}