<?php

namespace MoySklad\Components\Specs;

use MoySklad\Exceptions\UnknownSpecException;

abstract class AbstractSpecs{
    protected static $cachedDefaultSpecs = null;

    protected function __construct($specs = [])
    {
        $defaults = $this->getDefaults();
        foreach ( $defaults as $k=>$v ) {
            $this->{$k} = $v;
        }
        foreach ( $specs as $specName=>$spec ){
            if ( !array_key_exists($specName, $defaults) ){
                throw new UnknownSpecException($specName);
            }
            $this->{$specName} = $spec;
        }
        if ( empty($specs) ){
            static::$cachedDefaultSpecs = $this;
        }
    }

    public static function create($specs = []){
        if ( empty($specs) && static::$cachedDefaultSpecs !== null){
            return static::$cachedDefaultSpecs;
        }
        return new static($specs);
    }

    public function __get($name)
    {
        throw new UnknownSpecException($name);
    }

    abstract public function getDefaults();
}