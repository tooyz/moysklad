<?php

namespace MoySklad\Components\Fields;

use MoySklad\Exceptions\UnknownEntityException;
use MoySklad\Repositories\EntityRepository;

class MetaField extends AbstractFieldAccessor{

    private static $ep = null;

    public function __construct($fields)
    {
        if ( $fields instanceof static ) {
            parent::__construct($fields->getInternal());
        } else {
            parent::__construct($fields);
        }
        if ( static::$ep === null ){
            static::$ep = EntityRepository::instance();
        }
    }

    public function getClass(){
        if ( empty($this->type) ) return null;
        if ( !isset(static::$ep->entityNames[$this->type]) ){
            throw new UnknownEntityException($this->type);
        }
        return static::$ep->entityNames[$this->type];
    }

    public function getHref(){
        return $this->href;
    }

    public function parseRelationHref(){
        $eHref = explode('/', $this->href);
        $cntHref = count($eHref);
        $entityClass = $eHref[$cntHref - 3];
        $entityId = $eHref[$cntHref - 2];
        $relationClass = $eHref[$cntHref - 1];
        return compact('entityClass', 'entityId', 'relationClass');
    }

    public function getId(){
        if ( !empty($this->href) ){
            $exp = explode("/", $this->href);
            return $exp[count($exp) - 1];
        }
        return null;
    }

    public static function getClassFromPlainMeta($metaField){
        $meta = new static($metaField);
        return $meta->getClass();
    }
}