<?php

namespace MoySklad\Lists;

use MoySklad\Components\Fields\MetaField;
use MoySklad\Components\MassRequest;
use MoySklad\Entities\AbstractEntity;
use MoySklad\MoySklad;

class EntityList implements \JsonSerializable, \ArrayAccess {
    protected
        $skladInstance,
        $items = [];

    public function __construct(MoySklad $skladInstance, $items = [], MetaField $metaField = null)
    {
        $this->skladInstance = $skladInstance;
        $this->replaceItems($items);
    }

    public function replaceItems($items){
        if ( $items instanceof EntityList ){
            $this->items = $items->toArray();
        } else if ( !is_array($items) ) {
            $this->items = [$items];
        } else {
            $this->items = $items;
        }
    }

    /**
     * @param callable $cb
     * @return EntityList $this
     */
    public function each(callable $cb){
        $this->getIterator()->each($cb);
        return $this;
    }

    public function merge(EntityList $list){
        return new static($this->skladInstance, array_merge($this->items, $list->toArray()));
    }

    public function map(callable $cb){
        return new static($this->skladInstance, array_map($cb, $this->items));
    }

    public function filter(callable $cb){
        return new static($this->skladInstance, array_filter($this->items, $cb));
    }

    public function reduce(callable $cb, $initial = null){
        return array_reduce($this->items, $cb, $initial);
    }

    public function transformItemsToClass($targetClass){
        $this->items = array_map(function(AbstractEntity $e) use($targetClass){
            return $e->transformToClass($targetClass);
        }, $this->items);
        return $this;
    }

    public function transformItemsToMetaClass(){
        $this->items = array_map(function(AbstractEntity $e){
            return $e->transformToMetaClass();
        }, $this->items);
        return $this;
    }

    public function massCreate(){
        $mr = new MassRequest($this->skladInstance, $this->items);
        $this->items = $mr->create()->toArray();
        return $this;
    }

    public function getIterator(){
        return new ListIterator($this->items);
    }

    public function push(AbstractEntity $entity){
        $this->items[] = $entity;
    }

    /**
     * @param $key
     * @return AbstractEntity
     */
    public function get($key){
        return $this->items[$key];
    }

    public function set($key, $value){
        $this->items[$key] = $value;
        return $this;
    }

    public function count(){
        return count($this->items);
    }

    public function toArray(){
        return $this->items;
    }

    function jsonSerialize()
    {
        return $this->toArray();
    }

    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }
}