<?php

namespace MoySklad\Lists;


use MoySklad\Components\Fields\MetaField;
use MoySklad\Components\ListQuery;
use MoySklad\MoySklad;
use MoySklad\Components\ListQuery\RelationListQuery;
use MoySklad\Providers\RequestUrlProvider;

/**
 * EntityList with meta. Used for query
 * Class RelationEntityList
 * @package MoySklad\Lists
 */
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

    /**
     * Get RelationListQuery object which van be used for getting, filtering and searching lists defined in meta
     * @see ListQuery
     * @return RelationListQuery
     * @throws \MoySklad\Exceptions\UnknownEntityException
     */
    public function listQuery(){
        $relHref = $this->meta->parseRelationHref();
        $res = new RelationListQuery($this->skladInstance, $this->meta->getClass());
        $res->setCustomQueryUrl(
            RequestUrlProvider::instance()->relationListUrl($relHref['entityClass'], $relHref['entityId'], $relHref['relationClass'])
        );
        return $res;
    }

    /**
     * @see EntityList::merge()
     * @param EntityList $list
     * @return static
     */
    public function merge(EntityList $list){
        return new static($this->skladInstance, array_merge($this->items, $list->toArray()), $this->meta);
    }

    /**
     * @see EntityList::map()
     * @param callable $cb
     * @return static
     */
    public function map(callable $cb){
        return new static($this->skladInstance, array_map($cb, $this->items), $this->meta);
    }


    /**
     * @see EntityList::filter()
     * @param callable $cb
     * @return static
     */
    public function filter(callable $cb){
        return new static($this->skladInstance, array_filter($this->items, $cb), $this->meta);
    }
}