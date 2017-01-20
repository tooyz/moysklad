<?php

namespace MoySklad\Components;

class Expand{
    private
        $params = [];

    private function __construct( $params )
    {
        $this->params = $params;
    }

    public static function create($params){
        if ( !is_array($params) ) throw new \Exception('Expand pararms must be an array');
        return new static($params);
    }

    public function flatten(){
        return implode(',', $this->params);
    }
}