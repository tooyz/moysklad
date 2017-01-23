<?php

namespace MoySklad\Lists;


use MoySklad\Components\Fields\MetaField;
use MoySklad\Components\ListQuery;
use MoySklad\MoySklad;
use MoySklad\Components\ListQuery\RelationListQuery;
use MoySklad\Providers\RequestUrlProvider;

class RelationEntityList extends EntityList{
    /**
     * @var null|MetaField
     */
    public $meta = null;

    public function __construct(MoySklad $skladInstance, array $items, MetaField $metaField)
    {
        parent::__construct($skladInstance, $items);
        $this->meta = $metaField;
    }

    public function setMeta(MetaField $metaField){
        $this->meta = $metaField;
    }

    public function listQuery(){
        $eHref = explode('/', $this->meta->getHref());
        $cntHref = count($eHref);
        $entityClass = $eHref[$cntHref - 3];
        $entityId = $eHref[$cntHref - 2];
        $relationClass = $eHref[$cntHref - 1];
        $res = new RelationListQuery($this->skladInstance, $this->meta->getClass(), $this);
        $res->setCustomQueryUrl(
            RequestUrlProvider::instance()->relationListUrl($entityClass, $entityId, $relationClass)
        );
        return $res;
    }
}