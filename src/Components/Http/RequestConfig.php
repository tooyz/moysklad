<?php

namespace MoySklad\Components\Http;

class RequestConfig{
    /**
     * @var array
     */
    private $fields = [
        "usePosApi" => false,
        "usePosToken" => false,
        "ignoreRequestBody" => false,
        "followRedirects" => true
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
        $this->checkKey($key);
        return $this->fields[$key];
    }

    /**
     * @param $key
     * @param $value
     * @throws \Exception
     */
    public function set($key, $value){
        $this->checkKey($key);
        $this->fields[$key] = $value;
    }

    /**
     * @param $key
     * @throws \Exception
     */
    private function checkKey($key){
        if ( !isset($this->fields[$key]) ) throw new \Exception("Unknown option '$key'");
    }
}
