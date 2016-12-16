<?php

namespace Tests\Cases;

use Faker\Generator;
use MoySklad\MoySklad;
use Tests\Config;
use Faker\Factory as Faker;

require_once "../vendor/autoload.php";
require_once "../../vendor/autoload.php";
require_once "../includes.php";

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
    * @var MoySklad $sklad
    */
    protected $sklad;
    /**
    *@var Generator $faker
    */
    protected $faker;

    public function setUp()
    {
        $auth = Config::getAuthData();
        $this->sklad = new MoySklad($auth->login, $auth->password);
        $this->faker = Faker::create('ru_RU');
    }

    public function tearDown()
    {
    }

    protected function say($info){
        if ( is_array($info) || is_object($info) ){
            fwrite(STDOUT, print_r($info, 1));
        } else if ( is_string($info) ){
            fwrite(STDERR, $info);
        }
    }
}
