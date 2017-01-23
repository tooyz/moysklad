<?php

namespace MoySklad\Components\ListQuery;

use MoySklad\Components\FilterQuery;
use MoySklad\Components\Specs\QuerySpecs;
use MoySklad\Lists\RelationEntityList;
use MoySklad\MoySklad;

class RelationListQuery extends ListQuery{

    public function __construct(MoySklad $skladInstance, $entityClass)
    {
        parent::__construct($skladInstance, $entityClass);
    }

}