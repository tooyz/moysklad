<?php

namespace MoySklad\Providers;

use MoySklad\Interfaces\ISingleton;
use MoySklad\Utils\AbstractSingleton;

class RequestUrlProvider implements ISingleton {
    protected static $instance = null;

    public function getCreateUrl($entityName){
        return 'entity/' . $entityName;
    }

    public static function instance()
    {
        static::$instance?:static::$instance = new static();
        return static::$instance;
    }
}