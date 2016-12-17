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
        /**
         * @var Product $pl
         */
        $pl = Product::getList($this->sklad)[0];
        $oldName = $pl->name;
        $newName = $this->faker->linuxProcessor;
        $pl->name = $newName;
        $uProd = $pl->update();
        $this->assertNotEquals(
            $oldName,
            $uProd->name
        );
    }
}