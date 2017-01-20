<?php

namespace Tests\Cases;

use MoySklad\Components\Http\RequestLog;
use MoySklad\Entities\Products\Product;
use MoySklad\Exceptions\ApiResponseException;
use MoySklad\Exceptions\RequestFailedException;
use MoySklad\Lists\EntityList;

require_once "TestCase.php";

class SearchTest extends TestCase{

    public function setUp()
    {
        parent::setUp();
    }

    public function testMassCreateAndSearch(){
        $this->methodStart();
        $productName = "MassCreateProductTest__";
        $el = new EntityList($this->sklad);
        foreach ( range(1, 5) as $i ){
            $el->push(
                (new Product($this->sklad, [
                    "name" => $productName . (rand() + $i)
                ]))
            );
        }
        $el->massCreate();
        $ids = $el->map(function(Product $p){return $p->id;})->toArray();
        $this->say("Created products ids: " . \json_encode($ids));
        $foundProducts =  Product::listQuery($this->sklad)->search($productName);
        $this->say("Found " . $foundProducts->count() . " products");
        $foundProductsIds = $foundProducts->
                            map(function(Product $p) use($ids){
                                $this->assertTrue(in_array($p->id, $ids));
                                return $p->id;
                            });
        $this->say("Found products ids: " . \json_encode($foundProductsIds->toArray()));
        $foundProductsIds->each(function($pId){
            /**
             * @var Product $pId
             */
            $this->say("Deleting product with id: " . $pId);
            (new Product($this->sklad, ['id' => $pId]))->delete();
        });
        $this->methodEnd();
    }
}