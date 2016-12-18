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
        $cl = get_called_class();
        if ( empty($specs) && $cl::$cachedDefaultSpecs !== null){
            return $cl::$cachedDefaultSpecs;
        }
        return new static($specs);
    }

    public function toArray(){
        return (array)$this;
    }

    public function __get($name)
    {
        throw new UnknownSpecException($name);
    }

    abstract public function getDefaults();
}