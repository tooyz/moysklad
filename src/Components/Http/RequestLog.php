<?php

namespace MoySklad\Components\Http;

abstract class RequestLog{
    private static
        $history = [];

    public static function add($row){
        self::$history[microtime()] = $row;
    }

    public static function getLast(){
        return self::$history[count(self::$history) - 1];
    }
}