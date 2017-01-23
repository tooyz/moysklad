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
        $relHref = $this->meta->parseRelationHref();
        $res = new RelationListQuery($this->skladInstance, $this->meta->getClass());
        $res->setCustomQueryUrl(
            RequestUrlProvider::instance()->relationListUrl($relHref['entityClass'], $relHref['entityId'], $relHref['relationClass'])
        );
        return $res;
    }

    public function merge(EntityList $list){
        return new static($this->skladInstance, array_merge($this->items, $list->toArray()), $this->meta);
    }

    public function map(callable $cb){
        return new static($this->skladInstance, array_map($cb, $this->items), $this->meta);
    }

    public function filter(callable $cb){
        return new static($this->skladInstance, array_filter($this->items, $cb), $this->meta);
    }
}