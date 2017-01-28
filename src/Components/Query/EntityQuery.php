<?php

namespace MoySklad\Components\Query;

use MoySklad\Components\Expand;
use MoySklad\Components\Fields\MetaField;
use MoySklad\Components\FilterQuery;
use MoySklad\Components\Specs\QuerySpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Lists\EntityList;
use MoySklad\MoySklad;
use MoySklad\Repositories\RequestUrlRepository;
use MoySklad\Traits\AccessesSkladInstance;

class EntityQuery extends AbstractQuery {
    protected static $entityListClass = EntityList::class;
    /**
     * Get entity by id
     * @param MoySklad $skladInstance
     * @param $id
     * @param Expand|null $expand
     * @return AbstractEntity
     */
    public function byId($id, Expand $expand = null){
        $res = $this->getSkladInstance()->getClient()->get(
            RequestUrlRepository::instance()->getByIdUrl($this->entityName, $id),
            ($expand?['expand'=>$expand->flatten()]:[])
        );
        return new $this->entityClass($this->getSkladInstance(), $res);
    }
}