<?php

namespace MoySklad\Components\ListQuery;

use MoySklad\Components\FilterQuery;
use MoySklad\Components\Specs\QuerySpecs;
use MoySklad\Lists\RelationEntityList;
use MoySklad\MoySklad;

class RelationListQuery extends ListQuery{
    protected static $entityListClass = RelationEntityList::class;
}