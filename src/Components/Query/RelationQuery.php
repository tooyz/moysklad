<?php

namespace MoySklad\Components\Query;

use MoySklad\Components\Expand;
use MoySklad\Exceptions\UnsupportedOperationException;
use MoySklad\Lists\RelationEntityList;

class RelationQuery extends AbstractQuery {
    protected static $entityListClass = RelationEntityList::class;
}