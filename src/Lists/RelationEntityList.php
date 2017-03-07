<?php

namespace MoySklad\Lists;

use MoySklad\Components\Fields\MetaField;
use MoySklad\Components\Query\RelationQuery;
use MoySklad\MoySklad;
use MoySklad\Repositories\ApiUrlRepository;

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
     * @return RelationQuery
     * @throws \MoySklad\Exceptions\UnknownEntityException
     */
    public function query(){
        $relHref = $this->meta->parseRelationHref();
        $res = new RelationQuery($this->getSkladInstance(), $this->meta->getClass());
        $res->setCustomQueryUrl(
            ApiUrlRepository::instance()->getRelationListUrl($relHref['entityClass'], $relHref['entityId'], $relHref['relationClass'])
        );
        return $res;
    }

    /**
     * @see EntityList::merge()
     * @param EntityList $list
     * @return static
     */
    public function merge(EntityList $list){
        return new static($this->getSkladInstance(), array_merge($this->items, $list->toArray()), $this->meta);
    }

    /**
     * @see EntityList::map()
     * @param callable $cb
     * @return static
     */
    public function map(callable $cb){
        return new static($this->getSkladInstance(), array_map($cb, $this->items), $this->meta);
    }


    /**
     * @see EntityList::filter()
     * @param callable $cb
     * @return static
     */
    public function filter(callable $cb){
        return new static($this->getSkladInstance(), array_filter($this->items, $cb), $this->meta);
    }
}