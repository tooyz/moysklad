<?php

namespace MoySklad\Components\Fields;

use MoySklad\Exceptions\UnknownEntityException;
use MoySklad\Repositories\EntityRepository;

/**
 * "meta" field of entity
 * Class MetaField
 * @package MoySklad\Components\Fields
 */
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

    /**
     * Try to get class from type field
     * @return null
     * @throws UnknownEntityException
     */
    public function getClass(){
        if ( empty($this->type) ) return null;
        if ( !isset(static::$ep->entityNames[$this->type]) ){
            throw new UnknownEntityException($this->type);
        }
        return static::$ep->entityNames[$this->type];
    }

    /**
     * @return string
     */
    public function getHref(){
        return $this->href;
    }

    /**
     * Get relation link in meta
     * @return array
     */
    public function parseRelationHref(){
        $eHref = explode('/', $this->href);
        $cntHref = count($eHref);
        $entityClass = $eHref[$cntHref - 3];
        $entityId = $eHref[$cntHref - 2];
        $relationClass = $eHref[$cntHref - 1];
        return compact('entityClass', 'entityId', 'relationClass');
    }

    /**
     * Try to get entity id in meta
     * @return null
     */
    public function getId(){
        if ( !empty($this->href) ){
            $exp = explode("/", $this->href);
            return $exp[count($exp) - 1];
        }
        return null;
    }

    /**
     * Returns class from stdClass/array meta object
     * @param $metaField
     * @return string
     * @throws UnknownEntityException
     */
    public static function getClassFromPlainMeta($metaField){
        return (new static($metaField))->getClass();
    }
}