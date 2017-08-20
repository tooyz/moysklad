<?php

namespace MoySklad\Components\Http;

abstract class RequestLog{
    private static
        $history = [],
        $total = 0,
        $storageSize = 50;

    public static function setStorageSize($size){
        if ( is_int($size) ) self::$storageSize = $size;
    }

    public static function add($row){
        self::$total++;
        self::$history[] = $row;
        if ( self::$storageSize !== 0 && count(self::$history) > self::$storageSize ){
            array_shift(self::$history);
        }
    }

    public static function replaceLast($row){
        self::$history[count(self::$history) - 1] = $row;
    }

    public static function getLast(){
        return self::$history[count(self::$history) - 1];
    }

    public static function getRequestList(){
        return array_map(function($row){
            return $row['req'];
        }, self::$history);
    }

    public static function getList(){
        return [
            "history" => self::$history,
            "total" => self::$total
        ];
    }
}
