<?php

namespace MoySklad\Components\Query;

use MoySklad\Components\Expand;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Lists\EntityList;
use MoySklad\Registers\ApiUrlRegistry;

class EntityQuery extends AbstractQuery {
    protected static $entityListClass = EntityList::class;

    /**
     * Get entity by id
     * @param $id
     * @param Expand|null $expand Deprecated, use withExpand()
     * @return AbstractEntity
     * @throws \Throwable
     */
    public function byId($id, Expand $expand = null){
        if ( !$expand ) $expand = $this->expand;
        $res = $this->getSkladInstance()->getClient()->get(
            ApiUrlRegistry::instance()->getByIdUrl($this->entityName, $id),
            ($expand?['expand'=>$expand->flatten()]:[]),
            $this->requestOptions
        );
        return new $this->entityClass($this->getSkladInstance(), $res);
    }
}
