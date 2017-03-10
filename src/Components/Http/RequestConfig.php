<?php

namespace MoySklad\Components\Http;

class RequestConfig{
    /**
     * @var array
     */
    private $fields = [
        "usePosApi" => false,
        "usePosToken" => false,
        "ignoreRequestBody" => false
    ];

    public function __construct($fields = []){
        $this->fields = array_merge($this->fields, $fields);
    }

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public function get($key){
        if ( !$key ) throw new \Exception("Unknown option '$key'");
        return $this->fields[$key];
    }
}