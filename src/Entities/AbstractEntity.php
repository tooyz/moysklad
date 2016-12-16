<?php

namespace MoySklad\Entities;

use MoySklad\Components\Fields\EntityRelation;
use MoySklad\Components\MassRequest;
use MoySklad\Components\Specs\ConstructionSpecs;
use MoySklad\Lists\EntityList;
use MoySklad\MoySklad;
use MoySklad\Components\Fields\EntityFields;
use MoySklad\Components\EntityLinker;
use MoySklad\Providers\RequestUrlProvider;

abstract class AbstractEntity implements \JsonSerializable {
    const MAX_LIST_LIMIT = 100;
    public static $entityName = '_a_entity';
    public $fields;
    public $links;
    protected $skladInstance;

    public function __construct(MoySklad &$skladInstance, $fields = [], ConstructionSpecs $specs = null)
    {
        if ( !$specs ) $specs = new ConstructionSpecs();
        if ( is_array($fields) === false && is_object($fields) === false) $fields = [$fields];
        $this->fields = new EntityFields($fields);
        $this->links = new EntityLinker();
        $this->skladInstance = $skladInstance;
        $this->processConstructionSpecs($specs);
    }

    protected function processConstructionSpecs(ConstructionSpecs $specs){
        if ( $specs->relations ){
            EntityRelation::setupRelations($this->skladInstance, $this->fields);
        }
    }

    /**
     * @param $targetClass
     * @return mixed| AbstractEntity
     */
    public function transformToClass($targetClass){
        return new $targetClass($this->skladInstance, $this->fields->getInternal());
    }

    public function transformToMetaClass(){
        $eMeta = $this->getMeta();
        if ( $eMeta ){
            return $this->transformToClass(
                $eMeta->getClass()
            );
        }
        return $this;
    }

    public function getMeta(){
        return $this->fields->getMeta();
    }

    /**
     * @param MoySklad $skladInstance
     * @param array $queryParams
     * @param array $options
     * @return array|EntityList
     */
    public static function getList(MoySklad &$skladInstance, $queryParams = [], $options = []){
        $limit = &$queryParams['limit'];
        $offset = &$queryParams['offset'];

        if ( !$limit ){
            $limit = self::MAX_LIST_LIMIT;
        }
        if ( !$offset ){
            $offset = 0;
        }
        $res = $skladInstance->getClient()->get(
            'entity/' . static::$entityName,
            $queryParams
        );
        $resultingObjects = new EntityList(
            $skladInstance,
            array_map(function($e) use($skladInstance){
                return new static($skladInstance, $e);
            }, $res->rows)
        );

        if ( $res->meta->size > $limit + $offset ){
            $offset += self::MAX_LIST_LIMIT;
            $resultingObjects = $resultingObjects->merge(self::getList($skladInstance, $queryParams));
        }
        return $resultingObjects;
    }

    public static function byId(MoySklad &$skladInstance, $id){
        $res = $skladInstance->getClient()->get(
          'entity/' . static::$entityName . '/' . $id
        );
        return new static($skladInstance, $res);
    }

    public function mergeFieldsWithLinks(){
        $res = [];
        $links = $this->links->getLinks();
        foreach ($this->fields as $k => $v){
            $res[$k] = $v;
        }
        foreach ( $links as $k=>$v ){
            $res[$k] = $v;
        }
        return $res;
    }

    public function getSkladInstance(){
        return $this->skladInstance;
    }
    
    function jsonSerialize()
    {
        return $this->fields;
    }

    function __get($name)
    {
        return $this->fields->{$name};
    }

    function __set($name, $value)
    {
        $this->fields->{$name} = $value;
    }

    function __isset($name)
    {
        return isset($this->fields->{$name});
    }
}