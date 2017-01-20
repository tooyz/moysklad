<?php

namespace MoySklad\Entities;

use MoySklad\Components\Fields\AttributeCollection;
use MoySklad\Components\Fields\EntityRelation;
use MoySklad\Components\Fields\MetaField;
use MoySklad\Components\Specs\ConstructionSpecs;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Exceptions\EntityHasNoIdException;
use MoySklad\Lists\EntityList;
use MoySklad\Components\ListQuery;
use MoySklad\MoySklad;
use MoySklad\Components\Fields\EntityFields;
use MoySklad\Components\EntityLinker;
use MoySklad\Providers\RequestUrlProvider;

abstract class AbstractEntity implements \JsonSerializable {
    public static $entityName = '_a_entity';
    /**
     * @var EntityFields $fields
     */
    public $fields;
    /**
     * @var EntityLinker $links
     */
    public $links;
    /**
     * @var EntityRelation|null $relations
     */
    public $relations = null;
    /**
     * @var MetaField $meta
     */
    public $meta;
    /**
     * @var AttributeCollection $attributes
     */
    public $attributes;
    /**
     * @var MoySklad $skladInstance
     */
    protected $skladInstance;

    public function __construct(MoySklad &$skladInstance, $fields = [], ConstructionSpecs $specs = null)
    {
        if ( !$specs ) $specs = ConstructionSpecs::create();
        if ( is_array($fields) === false && is_object($fields) === false) $fields = [$fields];
        $this->fields = new EntityFields($fields);
        $this->links = new EntityLinker();
        $this->skladInstance = $skladInstance;
        $this->relations = new EntityRelation([]);
        $this->processConstructionSpecs($specs);
    }

    protected function processConstructionSpecs(ConstructionSpecs $specs){
        if ( $specs->relations ){
            $this->relations = EntityRelation::createRelations($this->skladInstance, $this);
            foreach ( $this->relations->getInternal() as $k=>$v ){
                $this->fields->deleteKey($k);
            }
        }
    }

    /**
     * @param $targetClass
     * @return mixed| AbstractEntity
     */
    public function transformToClass($targetClass){
        return new $targetClass($this->skladInstance, $this->fields->getInternal());
    }

    /**
     * @return $this|mixed|AbstractEntity
     */
    public function transformToMetaClass(){
        $eMeta = $this->getMeta();
        if ( $eMeta ){
            return $this->transformToClass(
                $eMeta->getClass()
            );
        }
        return $this;
    }

    /**
     * @return \MoySklad\Components\Fields\MetaField|null
     */
    public function getMeta(){
        return $this->fields->getMeta();
    }

    /**
     * @return static
     */
    public function update($getIdFromMeta = false){
        if ( empty($this->fields->id) ){
            if ( !$getIdFromMeta || !$id = $this->getMeta()->getId()) throw new EntityHasNoIdException($this);
        } else {
            $id = $this->id;
        }
        $res = $this->skladInstance->getClient()->put(
            RequestUrlProvider::instance()->getUpdateUrl(static::$entityName, $id),
            $this->mergeFieldsWithLinks()
        );
        return new static($this->skladInstance, $res);
    }

    /**
     * @return static
     */
    public function fresh(){
        $eId = $this->getMeta()->getId();
        return static::byId($this->skladInstance, $eId);
    }

    /**
     * @param bool $getIdFromMeta
     * @return bool
     * @throws EntityHasNoIdException
     */
    public function delete($getIdFromMeta = false){
        if ( empty($this->fields->id) ){
            if ( !$getIdFromMeta || !$id = $this->getMeta()->getId()) throw new EntityHasNoIdException($this);
        } else {
            $id = $this->id;
        }
        $this->skladInstance->getClient()->delete(
            RequestUrlProvider::instance()->getDeleteUrl(static::$entityName, $id)
        );
        return true;
    }

    /**
     * @param MoySklad $skladInstance
     * @return ListQuery
     */
    public static function listQuery(MoySklad &$skladInstance){
        return new ListQuery($skladInstance, static::class);
    }

    /**
     * @param MoySklad $skladInstance
     * @param $id
     * @return AbstractEntity
     */
    public static function byId(MoySklad &$skladInstance, $id){
        $res = $skladInstance->getClient()->get(
          RequestUrlProvider::instance()->getByIdUrl(static::$entityName, $id)
        );
        return new static($skladInstance, $res);
    }

    public function mergeFieldsWithLinks(){
        $res = [];
        $links = $this->links->getLinks();
        foreach ($this->fields->getInternal() as $k => $v){
            $res[$k] = $v;
        }
        foreach ( $links as $k=>$v ){
            $res[$k] = $v;
        }
        return $res;
    }

    public function copyRelationsToLinks(){
        foreach ($this->relations->getInternal() as $k=>$v){
            $this->links->link($v, LinkingSpecs::create([
                "name" => $k
            ]));
        }
        return $this;
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