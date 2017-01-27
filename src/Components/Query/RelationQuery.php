<?php

namespace MoySklad\Components\Query;

use MoySklad\Components\Expand;
use MoySklad\Exceptions\UnsupportedOperationException;
use MoySklad\Lists\RelationEntityList;

class RelationQuery extends Query{
    protected static $entityListClass = RelationEntityList::class;

    /**
     * @param $id
     * @param Expand|null $expand
     * @throws UnsupportedOperationException
     */
    public function byId($id, Expand $expand = null)
    {
        throw new UnsupportedOperationException("Cant query relation list by id");
    }


}