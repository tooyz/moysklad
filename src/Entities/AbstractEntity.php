<?php

namespace MoySklad\Entities;

use MoySklad\MoySklad;
use MoySklad\Utils\EntityFields;
use MoySklad\Utils\EntityLinker;

abstract class AbstractEntity implements \JsonSerializable {
    const MAX_LIST_LIMIT = 100;
    public static $entityName = 'entity';
    public $fields;
    public $links;
    protected $skladInstance;
    public $meta;

    public function __construct( MoySklad &$skladInstance, $fields = [])
    {
        if ( is_array($fields) === false && is_object($fields) === false) $fields = [$fields];
        $this->fields = new EntityFields($fields);
        $this->links = new EntityLinker();
        $this->skladInstance = $skladInstance;
    }

    public static function getList(MoySklad $skladInstance, $params = []){
        $limit = &$params['limit'];
        $offset = &$params['offset'];
        if ( !$limit ){
            $limit = self::MAX_LIST_LIMIT;
        }
        if ( !$offset ){
            $offset = 0;
        }
        $res = $skladInstance->getClient()->get(
            'entity/' . static::$entityName,
            $params
        );
        $res = array_map(function($e) use($skladInstance){
            return new static($skladInstance, $e);
        }, $res->rows);
        if ( $res->meta->size > $limit + $offset ){
            $offset += self::MAX_LIST_LIMIT;
            $res = array_merge($res, self::getList($skladInstance, $params));
        }
        return $res;
    }

    public static function byId(MoySklad $skladInstance, $id){
        $res = $skladInstance->getClient()->get(
          'entity/' . static::$entityName . '/' . $id
        );
        return new static($skladInstance, $res);
    }

    protected function _create(){
        $res = $this->skladInstance->getClient()->post(
            'entity/' . static::$entityName,
            $this->mergeFieldsWithLinks()
        );
        $this->fields->replace($res);
        return $this;
    }

    public function getSkladInstance(){
        return $this->skladInstance;
    }

    protected function mergeFieldsWithLinks(){
        $res = [];
        $links = $this->links->getLinks();
        foreach ($this->fields as $k => $v){
            $res[$k] = $v;
        }
        foreach ( $links as $k=>$v ){
            $res[$k] = $v;
        }
        return $res;
    }

    function jsonSerialize()
    {
        return $this->fields;
    }

    function __get($name)
    {
        return $this->fields->{$name};
    }

    function __set($name, $value)
    {
        $this->fields->{$name} = $value;
    }
}