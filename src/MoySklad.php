<?php

namespace MoySklad;

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

    private function __construct($login, $password, $posToken, $hashCode)
    {
        $this->client = new MoySkladHttpClient($login, $password, $posToken);
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
    public static function getInstance($login, $password, $posToken = null){
        $hash = self::makeHash($login, $password);
        if ( empty(self::$repository[$hash]) ){
            self::$repository[$hash] = new self($login, $password, $posToken, $hash);
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

    public function setPosToken($posToken){
        $this->client->setPosToken($posToken);
    }
}