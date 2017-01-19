<?php

namespace MoySklad\Components\Fields;

use MoySklad\Entities\Misc\Attribute;
use MoySklad\Exceptions\UnknownEntityException;
use MoySklad\Providers\EntityProvider;

class AttributeCollection extends AbstractFieldAccessor{

    private static $ep = null;

    public function __construct($fields)
    {
        $this->storage->attrs = [];
        if ( $fields instanceof static ) {
            parent::__construct($fields->getInternal());
        } else {
            parent::__construct(['attrs' => $fields]);
        }
        if ( self::$ep === null ){
            self::$ep = EntityProvider::instance();
        }
    }

    function add(Attribute $attribute){
        $this->storage->attrs[] = $attribute;
    }

    function getList(){
        return $this->storage->attrs;
    }

    function jsonSerialize()
    {
        return $this->storage->attrs;
    }
}