<?php

namespace MoySklad\Components\ListQuery;

use MoySklad\Components\FilterQuery;
use MoySklad\Components\Specs\QuerySpecs;
use MoySklad\Lists\RelationEntityList;
use MoySklad\MoySklad;

class RelationListQuery extends ListQuery{

    private
        $relationEntityList = null;

    public function __construct(MoySklad $skladInstance, $entityClass, RelationEntityList &$entityList)
    {
        parent::__construct($skladInstance, $entityClass);
        $this->relationEntityList = $entityList;
    }

    public function get(QuerySpecs $querySpecs = null)
    {
        $res = parent::get($querySpecs);
        $this->relationEntityList->replaceItems($res->toArray());
        return $this->relationEntityList;
    }

    public function search($searchString = '', QuerySpecs $querySpecs = null)
    {
        $res = parent::search($searchString, $querySpecs);
        $this->relationEntityList->replaceItems($res->toArray());
        return $this->relationEntityList;
    }

    public function filter(FilterQuery $filterQuery = null, QuerySpecs $querySpecs = null)
    {
        $res = parent::filter($filterQuery, $querySpecs);
        $this->relationEntityList->replaceItems($res->toArray());
        return $this->relationEntityList;
    }

}