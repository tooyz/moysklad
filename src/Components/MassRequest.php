<?php

namespace MoySklad\Components;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Exceptions\StackingRequestDifferentMethodException;
use MoySklad\MoySklad;
use MoySklad\Providers\RequestUrlProvider;

class MassRequest{
    private $skladInstance = null;
    /**
     * @var AbstractEntity[] $stack
     */
    private $stack = [];

    public function __construct(MoySklad $sklad, $stack = [])
    {
        $this->skladInstance = $sklad;
        if ( !is_array($stack) ) $stack = [$stack];
        foreach ($stack as $row){
            $this->stack[] = $row;
        }
    }

    public function push(AbstractEntity $entity){
        $this->stack[get_class($entity)][] = $entity;
    }

    public function create(){
        $className = get_class($this->stack[0]);
        $url = RequestUrlProvider::instance()->getCreateUrl($className::$entityName);
        $res = $this->skladInstance->getClient()->post(
            $url,
            array_map(function( AbstractEntity $e){
                return $e->mergeFieldsWithLinks();
            }, $this->stack)
        );
        return $this->recreateEntityList($className, $res);
    }

    private function recreateEntityList($className, $reqResult){
        $res = [];
        if ( is_array($reqResult) === false ) $reqResult = [$reqResult];
        foreach ($reqResult as $i=>$item){
            /**
             * @var AbstractEntity $newEntity
             */
            $newEntity = new $className($this->skladInstance, $item);
            $newEntity->links->reattachLinks($this->stack[$i]->links);
            $newEntity->fields->replace($this->stack[$i]->fields);
            $res[] = $newEntity;
        }
        return $res;
    }
}