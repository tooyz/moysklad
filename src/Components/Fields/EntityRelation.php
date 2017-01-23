<?php

namespace MoySklad\Components\Fields;

use MoySklad\Components\Expand;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Exceptions\Relations\RelationDoesNotExistException;
use MoySklad\Exceptions\Relations\RelationIsList;
use MoySklad\Exceptions\Relations\RelationIsSingle;
use MoySklad\Lists\EntityList;
use MoySklad\Lists\RelationEntityList;
use MoySklad\MoySklad;

class EntityRelation extends AbstractFieldAccessor {
    private $relatedByClass = null;

    public function __construct($fields, $relatedBy)
    {
        parent::__construct($fields);
        $this->relatedByClass = $relatedBy;
    }

    public static function createRelations(MoySklad $sklad, AbstractEntity &$entity){
        $internalFields = $entity->fields->getInternal();
        $foundRelations = [];
        foreach ($internalFields as $k=>$v){
            if ( is_array($v) || is_object($v) ){
                $ar = (array)$v;
                array_walk($ar, function($e, $i) use($k, $ar, &$foundRelations, $sklad){
                    if ( $i === 'meta' ){
                        $mf = new MetaField($e);
                        if ( isset($mf->size) ){
                            $foundRelations[$k] = new RelationEntityList($sklad, [], $mf);
                        } else {
                            $class = $mf->getClass();
                            if ( $class ){
                                $foundRelations[$k] = new $class($sklad, $ar);
                            }
                        }
                    }
                });
            }
        }
        return new static($foundRelations, get_class($entity));
    }


    public function loadSingleRelation($relationName, Expand $expand = null){
        $this->checkRelationExists($relationName);
        /**
         * @var AbstractEntity $rel
         */
        $rel = $this->storage->{$relationName};
        if ( $rel instanceof RelationEntityList ) throw new RelationIsList($relationName, $this->relatedByClass);
        $c = get_class($rel);
        return $c::byId($rel->getSkladInstance(), $rel->fields->meta->getId(), $expand);
    }

    public function getListQuery($relationName){
        $this->checkRelationExists($relationName);
        /**
         * @var RelationEntityList $rel
         */
        $rel = $this->storage->{$relationName};
        if ( $rel instanceof AbstractEntity ) throw new RelationIsSingle($relationName, $this->relatedByClass);
        return $rel->listQuery();
    }

    /**
     * @param $entityClass
     * @return static|null
     */
    public function find($entityClass){
        foreach ($this->storage as $key=>$value){
            if ( get_class($value) === $entityClass ) return $value;
        }
        return null;
    }

    public function getNames(){
        return array_keys((array)$this->storage);
    }

    private function checkRelationExists($relationName){
        if ( empty($this->storage->{$relationName}) ){
            throw new RelationDoesNotExistException($relationName, $this->relatedByClass);
        }
    }
}