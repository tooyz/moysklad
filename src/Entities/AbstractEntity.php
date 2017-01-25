<?php

namespace MoySklad\Entities;

use MoySklad\Components\Expand;
use MoySklad\Components\Fields\AttributeCollection;
use MoySklad\Components\Fields\EntityRelation;
use MoySklad\Components\Fields\MetaField;
use MoySklad\Components\MassRequest;
use MoySklad\Components\Specs\ConstructionSpecs;
use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Exceptions\ApiResponseException;
use MoySklad\Exceptions\EntityHasNoIdException;
use MoySklad\Exceptions\EntityHasNoMetaException;
use MoySklad\Components\ListQuery\ListQuery;
use MoySklad\MoySklad;
use MoySklad\Components\Fields\EntityFields;
use MoySklad\Components\EntityLinker;
use MoySklad\Providers\RequestUrlProvider;
use MoySklad\Traits\HasPlainCreation;

/**
 * Root entity object
 * Class AbstractEntity
 * @package MoySklad\Entities
 */
abstract class AbstractEntity implements \JsonSerializable {
    use HasPlainCreation;

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
        $this->relations = new EntityRelation([], static::class);
        $this->processConstructionSpecs($specs);
    }

    /**
     * @param ConstructionSpecs $specs
     */
    protected function processConstructionSpecs(ConstructionSpecs $specs){
        if ( $specs->relations ){
            $this->relations = EntityRelation::createRelations($this->skladInstance, $this);
            foreach ( $this->relations->getInternal() as $k=>$v ){
                $this->fields->deleteKey($k);
            }
        }
    }

    /**
     * Returns new AbstractEntity inheritor with chosen class
     * @param $targetClass
     * @return mixed| AbstractEntity
     */
    public function transformToClass($targetClass){
        return new $targetClass($this->skladInstance, $this->fields->getInternal());
    }

    /**
     * Returns new AbstractEntity inheritor with class taken from meta
     * @throws EntityHasNoMetaException
     * @return $this
     */
    public function transformToMetaClass(){
        $eMeta = $this->getMeta();
        if ( $eMeta ){
            return $this->transformToClass(
                $eMeta->getClass()
            );
        } else {
            throw new EntityHasNoMetaException($this);
        }
    }

    /**
     * Returns meta object
     * @return \MoySklad\Components\Fields\MetaField|null
     */
    public function getMeta(){
        return $this->fields->getMeta();
    }

    /**
     * Update entity with current fields
     * @param boolean $getIdFromMeta
     * @return static
     * @throws EntityHasNoIdException
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
     * Gets new entity with same id from server, expand may be used to load relations
     * @param boolean $getIdFromMeta
     * @param Expand|null $expand
     * @return AbstractEntity
     * @throws EntityHasNoMetaException
     */
    public function fresh($getIdFromMeta = false, Expand $expand = null){
        if ( empty($this->fields->id) ){
            if ( !$getIdFromMeta || !$id = $this->getMeta()->getId()) throw new EntityHasNoIdException($this);
        } else {
            $id = $this->id;
        }
        return static::byId($this->skladInstance, $id, $expand);
    }


    /**
     * Delete entity, throws exception if not found
     * @param bool $getIdFromMeta
     * @return bool
     * @throws EntityHasNoIdException
     * @throws ApiResponseException
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
     * Get ListQuery object which van be used for getting, filtering and searching lists
     * @param MoySklad $skladInstance
     * @return ListQuery
     */
    public static function listQuery(MoySklad &$skladInstance){
        return new ListQuery($skladInstance, static::class);
    }

    /**
     * Instantly runs creation on single entity
     * @param CreationSpecs $creationSpecs
     * @return static
     */
    protected function runCreateIfNotBatch(CreationSpecs $creationSpecs){
        if ( !$creationSpecs->batch ){
            $mr = new MassRequest($this->getSkladInstance(), $this);
            return $mr->create()[0];
        }
        return $this;
    }


    /**
     * Get entity by id
     * @param MoySklad $skladInstance
     * @param $id
     * @param Expand|null $expand
     * @return static
     */
    public static function byId(MoySklad &$skladInstance, $id, Expand $expand = null){
        $res = $skladInstance->getClient()->get(
            RequestUrlProvider::instance()->getByIdUrl(static::$entityName, $id),
            ($expand?['expand'=>$expand->flatten()]:[])
        );
        return new static($skladInstance, $res);
    }

    /**
     * Puts links to fields before creation
     * @internal
     * @return array
     */
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

    /**
     * Puts relations to links
     * @internal
     * @return $this
     */
    public function copyRelationsToLinks(){
        foreach ($this->relations->getInternal() as $k=>$v){
            $this->links->link($v, LinkingSpecs::create([
                "name" => $k
            ]));
        }
        return $this;
    }

    /**
     * Tries to load single relation defined on entity
     * @param $relationName
     * @param null $expand
     * @return mixed
     * @throws \MoySklad\Exceptions\Relations\RelationIsList
     */
    public function loadRelation($relationName, $expand = null){
        return $this->relations->loadSingleRelation($relationName, $expand);
    }

    /**
     * Get RelationListQuery object which van be used for getting, filtering and searching lists of relations
     * @param $relationName
     * @return \MoySklad\Components\ListQuery\RelationListQuery
     * @throws \MoySklad\Exceptions\Relations\RelationIsSingle
     */
    public function relationListQuery($relationName){
        return $this->relations->getListQuery($relationName);
    }

    /**
     * Get MoySklad instance used for constructing entity
     * @return MoySklad
     */
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