<?php

namespace Tests\Cases;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Assortment;
use MoySklad\Entities\Group;
use MoySklad\Entities\Products\Product;
use MoySklad\Entities\Products\Service;
use MoySklad\Lists\EntityList;

require_once "TestCase.php";

class EntityUpdateTest extends TestCase{

    public function setUp()
    {
        parent::setUp();
    }

    public function testSingleUpdate(){
        $this->methodStart();
        /**
         * @var Product $pl
         */
        $pl = Product::query($this->sklad)->getList()[0];
        $oldName = $pl->name;
        $newName = $this->faker->linuxProcessor;
        $pl->name = $newName;
        $uProd = $pl->buildUpdate()->execute();
        $this->assertNotEquals(
            $oldName,
            $uProd->name
        );
        $pl->name = $oldName;
        $pl->buildUpdate()->execute();
        $this->methodEnd();
    }
}