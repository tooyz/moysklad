<?php

namespace MoySklad\Utils;

class EntityFields implements \JsonSerializable {
    private $storage;

    public function __construct($fields)
    {
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

    function __get($name)
    {
        return $this->storage->{$name};
    }

    function __set($name, $value)
    {
        $this->storage->{$name} = $value;
    }

    function jsonSerialize()
    {
        return $this->getInternal();
    }
}