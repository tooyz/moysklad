<?php

namespace MoySklad\Entities;

use MoySklad\Components\Expand;
use MoySklad\Components\Fields\AttributeCollection;
use MoySklad\Components\Fields\EntityLinker;
use MoySklad\Components\Fields\EntityRelation;
use MoySklad\Components\Fields\MetaField;
use MoySklad\Components\MutationBuilders\CreationBuilder;
use MoySklad\Components\MutationBuilders\UpdateBuilder;
use MoySklad\Components\Query\EntityQuery;
use MoySklad\Components\Specs\ConstructionSpecs;
use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use MoySklad\Entities\Misc\Attribute;
use MoySklad\Entities\Misc\State;
use MoySklad\Exceptions\EntityCantBeMutatedException;
use MoySklad\Exceptions\EntityHasNoIdException;
use MoySklad\Exceptions\EntityHasNoMetaException;
use MoySklad\Interfaces\DoesNotSupportMutation;
use MoySklad\Lists\EntityList;
use MoySklad\MoySklad;
use MoySklad\Components\Fields\EntityFields;
use MoySklad\Repositories\ApiUrlRepository;
use MoySklad\Traits\AccessesSkladInstance;
use MoySklad\Traits\Deletes;

/**
 * Root entity object
 * Class AbstractEntity
 * @package MoySklad\Entities
 */
abstract class AbstractEntity implements \JsonSerializable {
    use AccessesSkladInstance, Deletes;

    /**
     * @var string
     */
    public static $entityName = '_a_entity';
    /**
     * @var null|string
     */
    protected static $customQueryUrl = null;
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

    public function __construct(MoySklad $skladInstance, $fields = [], ConstructionSpecs $specs = null)
    {
        if ( !$specs ) $specs = ConstructionSpecs::create();
        if ( is_array($fields) === false && is_object($fields) === false) $fields = [$fields];
        $this->fields = new EntityFields($fields);
        $this->links = new EntityLinker([]);
        $this->relations = new EntityRelation([], static::class);
        $this->skladHashCode = $skladInstance->hashCode();
        $this->processConstructionSpecs($specs);
    }

    /**
     * @param ConstructionSpecs $specs
     */
    protected function processConstructionSpecs(ConstructionSpecs $specs){
        if ( $specs->relations ){
            $this->relations = EntityRelation::createRelations($this->getSkladInstance(), $this);
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
        return new $targetClass($this->getSkladInstance(), $this->fields->getInternal());
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
     * Replaces current fields with response entity fields, expand may be used to load relations
     * @param Expand|null $expand
     * @return mixed
     * @throws EntityHasNoIdException
     */
    public function fresh(Expand $expand = null){
        if ( empty($this->fields->id) ){
            if ( !$id = $this->getMeta()->getId()) throw new EntityHasNoIdException($this);
        } else {
            $id = $this->id;
        }
        $queriedEntity = static::query($this->getSkladInstance())->byId($id, $expand);
        $this->replaceFields($queriedEntity);
        return $this;
    }

    /**
     * Copy fields and relations from other entity
     * @param AbstractEntity $entity
     * @return $this
     */
    public function replaceFields(AbstractEntity $entity){
        $this->fields = new EntityFields($entity->fields);
        $this->relations = new EntityRelation($entity->relations, get_class($this));
        return $this;
    }

    /**
     * Get EntityQuery object which van be used for getting, filtering and searching lists
     * @param MoySklad $skladInstance
     * @param QuerySpecs|null $querySpecs
     * @return EntityQuery
     */
    public static function query(MoySklad &$skladInstance, QuerySpecs $querySpecs = null){
        $eq = new EntityQuery($skladInstance, static::class, $querySpecs);
        if ( !is_null(static::$customQueryUrl) ){
            $eq->setCustomQueryUrl(static::$customQueryUrl);
        }
        return $eq;
    }

    /**
     * Get a CreationBuilder
     * @param CreationSpecs|null $specs
     * @return CreationBuilder
     */
    public function buildCreation(CreationSpecs $specs = null){
        $this->checkMutationPossibility();
        return new CreationBuilder($this, $specs);
    }

    /**
     * Get an UpdateBuilder
     * @return UpdateBuilder
     */
    public function buildUpdate(){
        $this->checkMutationPossibility();
        return new UpdateBuilder($this);
    }

    /**
     * Create with existing fields
     * @param CreationSpecs|null $specs
     * @return AbstractEntity
     * @throws \MoySklad\Exceptions\IncompleteCreationFieldsException
     */
    public function create(CreationSpecs $specs = null){
        $this->checkMutationPossibility();
        return $this->buildCreation($specs)->execute();
    }

    /**
     * Update with existing fields
     * @return AbstractEntity
     * @throws EntityHasNoIdException
     */
    public function update(){
        $this->checkMutationPossibility();
        return $this->buildUpdate()->execute();
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
        return $this->relations->fresh($relationName, $expand);
    }

    /**
     * Get RelationListQuery object which van be used for getting, filtering and searching lists of relations
     * @param $relationName
     * @return \MoySklad\Components\Query\RelationQuery
     * @throws \MoySklad\Exceptions\Relations\RelationIsSingle
     */
    public function relationListQuery($relationName){
        return $this->relations->listQuery($relationName);
    }

    /**
     * Get entity metadata information
     * @param MoySklad $sklad
     * @return \stdClass
     */
    public static function getMetaData(MoySklad $sklad){
        $res = $sklad->getClient()->get(
            ApiUrlRepository::instance()->getMetadataUrl(static::$entityName)
        );
        $attributes = (isset($res->attributes)?$res->attributes:[]);
        $attributes = new EntityList($sklad, $attributes);
        $res->attributes = $attributes->map(function($e) use($sklad){
            return new Attribute($sklad, $e);
        });
        $states = new EntityList($sklad, $res->states);
        $res->states = $states->map(function($e) use($sklad){
            return new State($sklad, $e);
        });
        return $res;
    }

    /**
     * @return array
     */
    public static function getFieldsRequiredForCreation(){
        return [];
    }

    protected function checkMutationPossibility(){
        if ( $this instanceof DoesNotSupportMutation ){
            throw new EntityCantBeMutatedException($this);
        }
    }
    
    function jsonSerialize()
    {
        $res = $this->fields->getInternal();
        $res->relations = $this->relations;
        return $res;
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