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

    /**
     * Replace fields with new
     * @param $fields
     */
    public function replace($fields){
        if(is_null($fields))
        {
            return false;
        }
        $this->storage = new \stdClass();
        if ( $fields instanceof static ) $fields = $fields->getInternal();
        foreach ( $fields as $fieldName => $field ){
            $this->storage->{$fieldName} = $field;
        }
    }

    /**
     * @return \stdClass
     */
    public function getInternal(){
        return $this->storage;
    }

    public function deleteKey($key){
        unset($this->storage->{$key});
    }

    public function __get($name)
    {
        return $this->storage->{$name};
    }

    public function __set($name, $value)
    {
        $this->storage->{$name} = $value;
    }

    public function __isset($name)
    {
        return isset($this->storage->{$name});
    }

    public function jsonSerialize()
    {
        return $this->getInternal();
    }
}
