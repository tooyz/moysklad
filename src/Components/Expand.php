<?php

namespace MoySklad\Components;

/**
 * Used for returning results with expanded relations
 * Class Expand
 * @package MoySklad\Components
 */
class Expand{
    private
        $params = [];

    private function __construct( $params )
    {
        $this->params = $params;
    }

    /**
     * Create an instance of expand
     * @param $params
     * @return static
     * @throws \Exception
     */
    public static function create($params){
        if ( !is_array($params) ) throw new \Exception('Expand params must be an array');
        return new static($params);
    }

    /**
     * Convert itself to string
     * @return string
     */
    public function flatten(){
        return implode(',', $this->params);
    }
}