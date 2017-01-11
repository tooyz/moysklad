<?php

namespace MoySklad\Components\Http;

abstract class RequestLog{
    private static
        $history = [],
        $total = 0,
        $storageSize = 10;

    public static function add($row){
        self::$total++;
        self::$history[microtime()] = $row;
        if ( count(self::$history) > self::$storageSize ){
            array_shift(self::$history);
        }
    }

    public static function getLast(){
        return array_values(self::$history)[count(self::$history) - 1];
    }

    public static function getList(){
        return [
            "history" => self::$history,
            "total" => self::$total
        ];
    }
}