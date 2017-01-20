<?php

namespace Tests\Cases;

use Faker\Generator;
use MoySklad\MoySklad;
use Tests\Config;
use Faker\Factory as Faker;

require_once "vendor/autoload.php";
require_once "../vendor/autoload.php";
require_once "includes.php";

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
    * @var MoySklad $sklad
    */
    protected $sklad;
    protected
        $lastTimer = null,
        $lastMethodName = null;
    /**
    *@var Generator $faker
    */
    protected $faker;

    public function setUp()
    {
        ob_end_flush();
        $auth = Config::getAuthData();
        $this->sklad = new MoySklad($auth->login, $auth->password);
        $this->faker = Faker::create('ru_RU');
    }

    public function tearDown()
    {
        ob_start();
    }

    protected function methodStart(){
        $func = debug_backtrace()[1]['function'];
        $this->say("\033[44m ".date("H:i:s").' '.$func." started\033[0m");
        $this->lastMethodName = $func;
    }

    protected function methodEnd(){
        $this->say("\033[35m ".date("H:i:s").' '.$this->lastMethodName." ended\033[0m");
    }

    protected function say($info){
        if ( is_array($info) || is_object($info) ){
            fwrite(STDOUT, print_r($info, 1) . PHP_EOL);
        } else if ( is_string($info) ){
            fwrite(STDERR, $info . PHP_EOL);
        }
    }

    protected function timeStart(){
        $this->lastTimer = microtime();
    }

    protected function timeEnd(){
        $this->lastTimer = null;
        return microtime() - $this->lastTimer;
    }

    protected function makeName($baseName){
        return $baseName . rand(1, 9999) . time();
    }
}
