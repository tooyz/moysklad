<?php

namespace MoySklad\Components\Fields;

class EntityFields extends AbstractFieldAccessor {

    /**
     * Replace fields. Creates MetaField and AttributeCollection within itself
     * @param $fields
     */
    public function replace($fields)
    {
        if ( $fields instanceof EntityFields ) $fields = $fields->getInternal();
        foreach ( $fields as $fieldName => $field ){
            if ( $fieldName === 'meta'){
                $this->storage->meta = new MetaField($field);
            }
            else if ( $fieldName === 'attributes' ){
                $this->storage->attributes = new AttributeCollection($field);
            }
            else {
                $this->storage->{$fieldName} = $field;
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