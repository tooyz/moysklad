<?php

namespace Tests\Cases;

use MoySklad\Entities\Products\Product;
use MoySklad\Lists\EntityList;
use MoySklad\MoySklad;
use Tests\Config;

require_once "TestCase.php";

class ListTest extends TestCase{
    public function setUp()
    {
        parent::setUp();
    }

    public function testListCreation(){
        $arLen = 10000;
        $ar = array_map(function($e) use(&$i){
            $i++;
            return new Product($this->sklad);
        }, range(0, $arLen - 1));
        $el = new EntityList($this->sklad, $ar);
        $this->assertTrue(
            $el->get($this->faker->numberBetween(0, $arLen)) instanceof Product
        );
        $el->push(new Product($this->sklad));
        $this->assertEquals(
            $arLen + 1,
            $el->count()
        );
    }

    public function testIterator(){
        $ar = [1, 2, 3];
        $i = 0;
        $el = new EntityList($this->sklad, $ar);
        $it = $el->getIterator();
        while ( $row = $it->next() ){
            $this->assertEquals(
                $ar[$i++],
                $row
            );
        }

        $i = 0;
        $el = new EntityList($this->sklad, [99, 11]);
        $el->each(function($e)use(&$i){
           $i++;
        });
        $this->assertEquals(2, $i);
    }

}