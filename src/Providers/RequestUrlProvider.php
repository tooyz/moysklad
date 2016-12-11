<?php

namespace MoySklad\Providers;

use MoySklad\Utils\AbstractSingleton;

class RequestUrlProvider extends AbstractSingleton {
    protected static $instance = null;

    public function getCreateUrl($entityName){
        return 'entity/' . $entityName;
    }
}