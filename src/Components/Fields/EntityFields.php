<?php

namespace MoySklad\Components\Fields;

class EntityFields extends AbstractFieldAccessor {

    public function replace($fields)
    {
        foreach ( $fields as $fieldName => $field ){
            if ( $fieldName === 'meta' ){
                $this->storage->meta = new MetaField($field);
            } else {
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