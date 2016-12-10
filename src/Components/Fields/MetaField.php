<?php

namespace MoySklad\Components\Fields;

use MoySklad\Exceptions\UnknownEntityException;
use MoySklad\Providers\EntityProvider;

class MetaField extends AbstractFieldAccessor{

    public function getClass(){
        $ep = EntityProvider::instance();
        if ( empty($this->type) ) return null;
        if ( !isset($ep->entityNames[$this->type]) ){
            throw new UnknownEntityException($this->type);
        }
        return $ep->entityNames[$this->type];
    }
}