<?php

namespace Tests\Cases;

use MoySklad\Entities\Organization;
use MoySklad\MoySklad;
use Tests\Config;

require_once "TestCase.php";

class JsonTest extends TestCase{
    public function setUp()
    {
        parent::setUp();
    }

    public function testJson(){
        $this->methodStart();
        $o = Organization::query($this->sklad)->getList()->get(0);
        $obj = \json_decode(\json_encode($o));
        $this->assertNotNull($obj->id);
        $this->assertNotNull($obj->relations->group);
        $this->methodEnd();
    }
}