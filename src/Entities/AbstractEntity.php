<?php

namespace MoySklad\Entities;

use MoySklad\Components\Expand;
use MoySklad\Components\Fields\AttributeCollection;
use MoySklad\Components\Fields\EntityFields;
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
use MoySklad\Entities\Audit\AbstractAudit;
use MoySklad\Entities\Audit\Audit;
use MoySklad\Entities\Audit\AuditEvent;
use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\Entities\Misc\Attribute;
use MoySklad\Entities\Misc\State;
use MoySklad\Exceptions\EntityCantBeMutatedException;
use MoySklad\Exceptions\EntityHasNoIdException;
use MoySklad\Exceptions\EntityHasNoMetaException;
use MoySklad\Exceptions\IncompleteCreationFieldsException;
use MoySklad\Interfaces\DoesNotSupportMutationInterface;
use MoySklad\Lists\EntityList;
use MoySklad\MoySklad;
use MoySklad\Registers\ApiUrlRegistry;
use MoySklad\Traits\AccessesSkladInstance;
use MoySklad\Traits\Deletes;
use stdClass;

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

    /**
     * AbstractEntity constructor.
     * @param MoySklad $skladInstance
     * @param array $fields
     * @param ConstructionSpecs|null $specs
     */
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
     * Returns new AbstractEntity inheritor with chosen class
     * @param $targetClass
     * @return mixed| AbstractEntity
     */
    public function transformToClass($targetClass){
        return new $targetClass($this->getSkladInstance(), $this->fields->getInternal());
    }

    /**
     * Returns new AbstractEntity inheritor with class taken from meta
     * @return $this
     * @throws EntityHasNoMetaException
     * @throws \MoySklad\Exceptions\UnknownEntityException
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
     * @return string
     * @throws EntityHasNoIdException
     */
    public function findEntityId(){
        $id = null;
        if ( empty($this->fields->id) ){
            if ( !$id = $this->getMeta()->getId()) throw new EntityHasNoIdException($this);
        } else {
            $id = $this->fields->id;
        }
        return $id;
    }

    /**
     * Replaces current fields with response entity fields, expand may be used to load relations
     * @param Expand|null $expand
     * @return mixed
     * @throws EntityHasNoIdException
     * @throws \Throwable
     */
    public function fresh(Expand $expand = null){
        $id = $this->findEntityId();
        $sklad = $this->getSkladInstance();
        $queriedEntity = static::query($sklad)->byId($id, $expand);
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
        $static = get_called_class();
        $eq = new EntityQuery($skladInstance, static::class, $querySpecs);
        $eq->setResponseAttributesMapper($static, "listQueryResponseAttributeMapper");
        if ( !is_null(static::$customQueryUrl) ){
            $eq->setCustomQueryUrl(static::$customQueryUrl);
        }
        return $eq;
    }

    /**
     * Get a CreationBuilder
     * @param CreationSpecs|null $specs
     * @return CreationBuilder
     * @throws EntityCantBeMutatedException
     */
    public function buildCreation(CreationSpecs $specs = null){
        $this->checkMutationPossibility();
        return new CreationBuilder($this, $specs);
    }

    /**
     * Get an UpdateBuilder
     * @return UpdateBuilder
     * @throws EntityCantBeMutatedException
     */
    public function buildUpdate(){
        $this->checkMutationPossibility();
        return new UpdateBuilder($this);
    }

    /**
     * Create with existing fields
     * @param CreationSpecs|null $specs
     * @return AbstractEntity|AbstractDocument
     * @throws EntityCantBeMutatedException
     * @throws IncompleteCreationFieldsException
     */
    public function create(CreationSpecs $specs = null){
        $this->checkMutationPossibility();
        return $this->buildCreation($specs)->execute();
    }

    /**
     * Update with existing fields
     * @return AbstractEntity
     * @throws EntityHasNoIdException
     * @throws EntityCantBeMutatedException
     * @throws \Throwable
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
     * @throws \Exception
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
     * @throws \MoySklad\Exceptions\Relations\RelationDoesNotExistException
     */
    public function loadRelation($relationName, $expand = null){
        return $this->relations->fresh($relationName, $expand);
    }

    /**
     * Get RelationListQuery object which van be used for getting, filtering and searching lists of relations
     * @param $relationName
     * @return \MoySklad\Components\Query\RelationQuery
     * @throws \MoySklad\Exceptions\Relations\RelationDoesNotExistException
     * @throws \MoySklad\Exceptions\Relations\RelationIsSingle
     * @throws \MoySklad\Exceptions\UnknownEntityException
     */
    public function relationListQuery($relationName){
        $static = get_called_class();
        $rq = $this->relations->listQuery($relationName);
        $rq->setResponseAttributesMapper($static, 'listQueryResponseAttributeMapper');
        return $rq;
    }

    public function getAuditEvents(){
        $eq = new EntityQuery($this->getSkladInstance(), AuditEvent::class);
        $eq->setResponseAttributesMapper(AbstractAudit::class, "listQueryResponseAttributeMapper");
        if ( static::class === Audit::class ){
            $eq->setCustomQueryUrl(ApiUrlRegistry::instance()->getAuditEventsUrl($this->fields->id));
        } else {
            $eq->setCustomQueryUrl(ApiUrlRegistry::instance()->getAuditEventsEntityUrl(
                static::$entityName, $this->fields->id
            ));
        }
        return $eq->getList();
    }

    /**
     * Get entity metadata information
     * @param MoySklad $sklad
     * @return stdClass
     * @throws \Throwable
     */
    public static function getMetaData(MoySklad $sklad){
        $res = $sklad->getClient()->get(
            ApiUrlRegistry::instance()->getMetadataUrl(static::$entityName)
        );
        $attributes = (isset($res->attributes)?$res->attributes:[]);
        $attributes = new EntityList($sklad, $attributes);
        $res->attributes = $attributes->map(function($e) use($sklad){
            return new Attribute($sklad, $e);
        });
        $states = new EntityList($sklad, isset($res->states) ? $res->states : []);
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

    /**
     * @throws IncompleteCreationFieldsException
     */
    public function validateFieldsRequiredForCreation(){
        $requiredFields = static::getFieldsRequiredForCreation();
        foreach ( $requiredFields as $requiredField ){
            if (
                !isset($this->links->{$requiredField}) && !isset($this->{$requiredField})
            ) throw new IncompleteCreationFieldsException($this);
        }
    }

    public static function boot(){}

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
     * @throws EntityCantBeMutatedException
     */
    protected function checkMutationPossibility(){
        if ( $this instanceof DoesNotSupportMutationInterface ){
            throw new EntityCantBeMutatedException($this);
        }
    }

    /**
     * @param stdClass $attributes
     * @return stdClass
     */
    public static function listQueryResponseAttributeMapper($attributes, $skladInstance){
        return $attributes;
    }

    public function jsonSerialize()
    {
        $res = $this->fields->getInternal();
        $res->relations = $this->relations;
        return $res;
    }

    public function __get($name)
    {
        return $this->fields->{$name};
    }

    public function __set($name, $value)
    {
        $this->fields->{$name} = $value;
    }

    public function __isset($name)
    {
        return isset($this->fields->{$name});
    }
}
