<?php

namespace MoySklad\Entities;

use MoySklad\Utils\MoySkladClient;

class Entity{
    /**
     * @var MoySkladClient $apiClient
     */
    private static $apiClient;
    private static $entityName = 'entity';

    public static function setClientInstance(MoySkladClient $moySkladInstance){
        self::$apiClient = $moySkladInstance;
    }

    public static function getList($params = []){
        return self::$apiClient->get(
            'entity/' . self::$entityName,
            $params
        );
    }


}