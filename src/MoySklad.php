<?php

namespace MoySklad;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Product;
use MoySklad\Exceptions\UnknownEntityException;
use MoySklad\Components\Http\MoySkladHttpClient;

class MoySklad{

    /**
     * @var MoySkladHttpClient
     */
    private $client;

    /**
     * @var string
     */
    private $hashCode;

    /**
     * @var MoySklad[]
     */
    private static $repository = [];

    private function __construct($login, $password, $hashCode)
    {
        $this->client = new MoySkladHttpClient($login, $password);
        $this->hashCode = $hashCode;
    }

    /**
     * Get hashcode for given login/password
     * @param $login
     * @param $password
     * @return string
     */
    private static function makeHash($login, $password){
        return crc32($login.$password);
    }

    /**
     * Use it instead of constructor
     * @param $login
     * @param $password
     * @return MoySklad
     */
    public static function getInstance($login, $password){
        $hash = self::makeHash($login, $password);
        if ( empty(self::$repository[$hash]) ){
            self::$repository[$hash] = new self($login, $password, $hash);
        }
        return self::$repository[$hash];
    }

    /**
     * Get instance with given hashcode
     * @param $hashCode
     * @return MoySklad
     */
    public static function findInstanceByHash($hashCode){
        return self::$repository[$hashCode];
    }

    /**
     * We're java now
     * @return string
     */
    public function hashCode(){
        return $this->hashCode;
    }

    /**
     * @return MoySkladHttpClient
     */
    public function getClient(){
        return $this->client;
    }
}