<?php

namespace Tests\Cases;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Assortment;
use MoySklad\Entities\Group;
use MoySklad\Entities\Products\Product;
use MoySklad\Entities\Products\Service;
use MoySklad\Lists\EntityList;

require_once "TestCase.php";

class EntityGetTest extends TestCase{

    public function setUp()
    {
        parent::setUp();
    }

    public function testGetProductList(){
        $productList = Product::getList($this->sklad);
        $this->assertTrue(
            $productList[0] instanceof Product
        );
        $assortmentList = Assortment::getList($this->sklad);
        echo "Start transform, have " . $assortmentList->count() . " items\n";
        $st = microtime();
        $assortmentList->transformItemsToMetaClass()
            ->each(function(AbstractEntity $e){
                $this->assertTrue(
                    $e instanceof Product ||
                    $e instanceof Service
                );
            });
        $et = microtime() - $st;
        echo "Took " . $et . " sec.";
        return $productList;
    }

    /**
     * @depends testGetProductList
     */
    public function testProductRelations(EntityList $productList){
        $product = $productList[0];
        $this->assertTrue(
            $product->group instanceof Group
        );
    }

    /**
     * @depends testGetProductList
     */
    public function testEntityRefresh(EntityList $productList){
        /**
         * @var Product $product
         */
        $product = $productList[0];
        $this->assertEquals(
            $product->id,
            $product->fresh()->id
        );
    }
}