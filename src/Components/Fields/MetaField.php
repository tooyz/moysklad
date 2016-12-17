<?php

namespace MoySklad\Components\Fields;

use MoySklad\Exceptions\UnknownEntityException;
use MoySklad\Providers\EntityProvider;

class MetaField extends AbstractFieldAccessor{

    private static $ep = null;

    public function __construct($fields)
    {
        if ( $fields instanceof static ) {
            parent::__construct($fields->getInternal());
        } else {
            parent::__construct($fields);
        }
        if ( self::$ep === null ){
            self::$ep = &EntityProvider::instance();
        }
    }

    public function getClass(){
        if ( empty($this->type) ) return null;
        if ( !isset(self::$ep->entityNames[$this->type]) ){
            throw new UnknownEntityException($this->type);
        }
        return self::$ep->entityNames[$this->type];
    }

    public function getHref(){
        return $this->href;
    }

    public function getId(){
        if ( !empty($this->href) ){
            $exp = explode("/", $this->href);
            return $exp[count($exp) - 1];
        }
    }

    public static function getClassFromPlainMeta($metaField){
        $meta = new static($metaField);
        return $meta->getClass();
    }
}