<?php

namespace MoySklad\Components\Fields;

/**
 * Class EntityFields
 * @package MoySklad\Components\Fields
 */
class EntityFields extends AbstractFieldAccessor {

    /**
     * Replace fields. Creates MetaField and AttributeCollection within itself
     * @param $fields
     */
    public function replace($fields)
    {
        if ( $fields instanceof EntityFields ) $fields = $fields->getInternal();
        foreach ( $fields as $fieldName => $field ){
            switch ( $fieldName ){
                case "meta":
                    $this->storage->meta = new MetaField($field);
                    break;
                case "attributes":
                    $this->storage->attributes = new AttributeCollection($field);
                    break;
                case "image":
                    $this->storage->image = new ImageField($field);
                    break;
                default:
                    $this->storage->{$fieldName} = $field;
                    break;
            }
        }
    }

    /**
     * @return MetaField|null
     */
    public function getMeta(){
        return $this->storage->meta?$this->storage->meta:null;
    }
}