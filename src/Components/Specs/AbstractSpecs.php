<?php

namespace MoySklad\Components\Specs;

use MoySklad\Exceptions\UnknownSpecException;

abstract class AbstractSpecs{
    public function __construct($specs = [])
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
    }

    public function __get($name)
    {
        throw new UnknownSpecException($name);
    }

    abstract public function getDefaults();
}