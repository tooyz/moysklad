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
        $this->methodEnd();
    }
}