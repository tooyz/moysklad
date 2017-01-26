<?php

namespace MoySklad\Entities\Documents\Movements;


use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Entities\Documents\Movements\Base\AbstractMovement;
use MoySklad\Entities\Organization;
use MoySklad\Entities\Store;
use MoySklad\Lists\EntityList;
use MoySklad\Traits\LinksPositionsForCreation;

class Enter extends AbstractMovement {
    public static $entityName = 'enter';
}