<?php

namespace MoySklad\Lists;

use MoySklad\Components\Fields\MetaField;
use MoySklad\Components\MassRequest;
use MoySklad\Entities\AbstractEntity;
use MoySklad\MoySklad;
use MoySklad\Traits\AccessesSkladInstance;

/**
 * List of entity objects
 * Class EntityList
 * @package MoySklad\Lists
 */
class EntityList implements \JsonSerializable, \ArrayAccess, \IteratorAggregate, \Countable {
    use AccessesSkladInstance;

    /**
     * @var array
     */
    protected
        $items = [],
        $attributes;

    public function __construct(MoySklad $skladInstance, $items = [], MetaField $metaField = null)
    {
        $this->skladHashCode = $skladInstance->hashCode();
        $this->replaceItems($items);
        $this->attributes = new \stdClass();
    }

    /**
     * Replace internal array
     * @param $items
     */
    public function replaceItems($items){
        if ( $items instanceof EntityList ){
            $this->items = $items->toArray();
        } else if ( !is_array($items) ) {
            $this->items = [$items];
        } else {
            $this->items = $items;
        }
    }

    public function replaceAttributes($attributes){
        $this->attributes = $attributes;
    }

    public function setAttribute($key, $value){
        $this->attributes->{$key} = $value;
    }

    public function getAttribute($key){
        return $this->attributes->{$key};
    }

    public function getMeta(){
        return isset($this->attributes->meta)?$this->attributes->meta:null;
    }

    public function getAttributes(){
        return $this->attributes;
    }

    /**
     * Iterate every item
     * @param callable $cb
     * @return EntityList $this
     */
    public function each(callable $cb){
        foreach ($this->items as $key => $item) {
            if ($cb($item, $key) === false) {
                break;
            }
        }
        return $this;
    }

    /**
     * @see array_merge
     * @param EntityList $list
     * @return static
     */
    public function merge(EntityList $list){
        return new static($this->getSkladInstance(), array_merge($this->items, $list->toArray()));
    }

    /**
     * @see array_map
     * @param callable $cb
     * @return static
     */
    public function map(callable $cb){
        return new static($this->getSkladInstance(), array_map($cb, $this->items));
    }

    /**
     * @see array_filter
     * @param callable $cb
     * @return static
     */
    public function filter(callable $cb){
        return new static($this->getSkladInstance(), array_filter($this->items, $cb));
    }

    /**
     * @see array_reduce
     * @param callable $cb
     * @param null $initial
     * @return mixed
     */
    public function reduce(callable $cb, $initial = null){
        return array_reduce($this->items, $cb, $initial);
    }

    /**
     * Transform stored items into target entity class
     * @param $targetClass
     * @return $this
     */
    public function transformItemsToClass($targetClass){
        $this->items = array_map(function(AbstractEntity $e) use($targetClass){
            return $e->transformToClass($targetClass);
        }, $this->items);
        return $this;
    }

    /**
     * Transform stored items into theirs entity class defined in meta
     * @return $this
     */
    public function transformItemsToMetaClass(){
        $this->items = array_map(function(AbstractEntity $e){
            return $e->transformToMetaClass();
        }, $this->items);
        return $this;
    }

    /**
     * Runs batch creation with stored items
     * @return $this
     */
    public function massCreate(){
        $mr = new MassRequest($this->getSkladInstance(), $this->items);
        $this->items = $mr->create()->toArray();
        return $this;
    }

    /**
     * Get iterator with stored items
     * @return \Iterator
     */
    public function getIterator(){
        return new ListIterator($this->items);
    }

    /**
     * Add item to list
     * @param AbstractEntity $entity
     */
    public function push(AbstractEntity $entity){
        $this->items[] = $entity;
    }

    /**
     * @see array_shift
     * @return mixed
     */
    public function shift(){
        return array_shift($this->items);
    }

    /**
     * @see array_unshift
     * @param $var
     * @param null $_
     * @return int
     */
    public function unshift($var, $_ = null){
        return array_unshift($this->items, $var, $_);
    }

    /**
     * @see array_pop
     * @return mixed
     */
    public function pop(){
        return array_pop($this->items);
    }

    /**
     * @see array_splice
     * @param $offset
     * @param null $length
     * @param null $replacement
     * @return array
     */
    public function splice($offset, $length = null, $replacement = null){
        return array_splice($this->items, $offset, $length, $replacement);
    }

    /**
     * Get object by offset
     * @param $key
     * @return AbstractEntity
     */
    public function get($key){
        return $this->items[$key];
    }

    /**
     * Store object by offset
     * @param $key
     * @param $value
     * @return $this
     */
    public function set($key, $value){
        $this->items[$key] = $value;
        return $this;
    }

    /**
     * Count stored objects
     * @return int
     */
    public function count(){
        return count($this->items);
    }

    /**
     * Get internal array
     * @return array
     */
    public function toArray(){
        return $this->items;
    }

    /**
     * Get json representation
     * @param $options
     * @return string
     */
    public function toJson($options){
        return json_encode($this->jsonSerialize(), $options);
    }

    public function jsonSerialize()
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

    public function __clone()
    {
        $newItems = [];
        /**
         * @var AbstractEntity $item
         */
        foreach ($this->items as $item){
            $newItems[] = clone $item;
        }
        $this->replaceItems($newItems);
    }
}
