<?php

namespace MoySklad\Components\Http;

abstract class RequestLog{
    private static
        $history = [],
        $total = 0,
        $storageSize = 50,
        $enabled = true;

    public static function setStorageSize($size){
        if ( is_int($size) ) self::$storageSize = $size;
    }

    /**
     * @param $row
     */
    public static function add($row){
        if ( !self::$enabled ) return;
        self::$total++;
        self::$history[] = $row;
        if ( self::$storageSize !== 0 && count(self::$history) > self::$storageSize ){
            array_shift(self::$history);
        }
    }

    /**
     * @param $row
     */
    public static function replaceLast($row){
        if ( !self::$enabled ) return;
        self::$history[count(self::$history) - 1] = $row;
    }

    /**
     * @return mixed|null
     */
    public static function getLast(){
        $idx = count(self::$history) - 1;
        if ( $idx >= 0 ){
            return self::$history[$idx];
        }
        return null;
    }

    /**
     * @return array
     */
    public static function getRequestList(){
        return array_map(function($row){
            return $row['req'];
        }, self::$history);
    }

    /**
     * @return array
     */
    public static function getList(){
        return [
            "history" => self::$history,
            "total" => self::$total
        ];
    }

    /**
     * Stop log collecting
     * @param bool $enabled
     * @return array
     */
    public static function setEnabled($enabled){
        self::$enabled = $enabled;
    }
}
