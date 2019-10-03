<?php

namespace MoySklad\Components\Fields;

use MoySklad\Entities\Misc\Attribute;
use MoySklad\Registers\EntityRegistry;

/**
 * "attributes" field of entity
 * Class AttributeCollection
 * @package MoySklad\Components\Fields
 */
class AttributeCollection extends AbstractFieldAccessor{

    private static $ep = null;

    public function __construct($fields)
    {
        if ( $fields instanceof static ) {
            parent::__construct($fields->getInternal());
        } else {
            parent::__construct(['attrs' => $fields]);
        }

        if ( self::$ep === null ){
            self::$ep = EntityRegistry::instance();
        }
    }

    /**
     * Append an attribute
     * @param Attribute $attribute
     */
    public function add(Attribute $attribute){
        $this->storage->attrs[] = $attribute;
    }

    /**
     * @return mixed
     */
    public function getList(){
        return $this->storage->attrs;
    }

    public function jsonSerialize()
    {
        return $this->storage->attrs;
    }
}
