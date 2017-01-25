<?php

namespace Tests\Cases;

use MoySklad\MoySklad;
use Tests\Config;

require_once "TestCase.php";

class AuthTest extends TestCase{
    public function setUp()
    {
        parent::setUp();
    }

    public function testConnection(){
        $this->methodStart();
        $res = $this->sklad->getClient()->get('entity/demand');
        $this->assertObjectHasAttribute(
            "context",
            $res
        );
        $sklad1 = MoySklad::getInstance("kok", "pok");
        $sklad2 = MoySklad::getInstance("kok", "pok");
        $this->assertTrue($sklad1 === $sklad2);
        $sklad3 = MoySklad::getInstance("t@pkek", "dogewow");
        $this->assertFalse($sklad1->hashCode() === $sklad3->hashCode());
        $this->methodEnd();
    }
}