<?php

namespace MoySklad\Components;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Lists\EntityList;
use MoySklad\MoySklad;
use MoySklad\Repositories\ApiUrlRegistry;
use MoySklad\Traits\AccessesSkladInstance;

/**
 * Used for requesting with multiple entities
 * Class MassRequest
 * @package MoySklad\Components
 */
class MassRequest{
    use AccessesSkladInstance;

    /**
     * @var AbstractEntity[] $stack
     */
    private $stack = [];

    public function __construct(MoySklad $sklad, $stack = [])
    {
        $this->skladHashCode = $sklad->hashCode();
        if ( !is_array($stack) ) $stack = [$stack];
        foreach ($stack as $row){
            $this->stack[] = $row;
        }
    }

    /**
     * Add entity to internal array
     * @param AbstractEntity $entity
     * @throws \Exception
     */
    public function push(AbstractEntity $entity){
        if ( !empty($this->stack) && get_class($this->stack[0]) !== get_class($entity) ){
            throw new \Exception("Mass request can only hold entities of same type");
        }
        $this->stack[] = $entity;
    }

    /**
     * Run creation for stored entities
     * @return EntityList
     */
    public function create(){
        $className = get_class($this->stack[0]);
        $url = ApiUrlRegistry::instance()->getCreateUrl($className::$entityName);
        $res = $this->getSkladInstance()->getClient()->post(
            $url,
            array_map(function( AbstractEntity $e){
                return $e->mergeFieldsWithLinks();
            }, $this->stack)
        );
        return $this->recreateEntityList($className, $res);
    }

    /**
     * Returns new EntityList after performing API operation
     * @param $className
     * @param $reqResult
     * @return EntityList
     */
    private function recreateEntityList($className, $reqResult){
        $res = [];
        if ( is_array($reqResult) === false ) $reqResult = [$reqResult];
        foreach ($reqResult as $i=>$item){
            /**
             * @var AbstractEntity $newEntity
             */
            $newEntity = new $className($this->getSkladInstance(), $item);
            $newEntity->links->reattachLinks($this->stack[$i]->links);
            $newEntity->fields->replace($this->stack[$i]->fields);
            $res[] = $newEntity;
        }
        return new EntityList($this->getSkladInstance(), $res);
    }
}
