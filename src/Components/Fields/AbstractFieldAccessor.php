<?php

namespace MoySklad\Components\Fields;

/**
 * Class for storing different fields
 * Class AbstractFieldAccessor
 * @package MoySklad\Components\Fields
 */
abstract class AbstractFieldAccessor implements \JsonSerializable {
    protected $storage;

    public function __construct($fields)
    {
        $this->storage = new \stdClass();
        $this->replace($fields);
    }

    public function replace($fields){
        $this->storage = new \stdClass();
        foreach ( $fields as $fieldName => $field ){
            $this->storage->{$fieldName} = $field;
        }
    }

    public function getInternal(){
        return $this->storage;
    }

    public function deleteKey($key){
        unset($this->storage->{$key});
    }

    function __get($name)
    {
        return $this->storage->{$name};
    }

    function __set($name, $value)
    {
        $this->storage->{$name} = $value;
    }

    function __isset($name)
    {
        return isset($this->storage->{$name});
    }

    function jsonSerialize()
    {
        return $this->getInternal();
    }
}