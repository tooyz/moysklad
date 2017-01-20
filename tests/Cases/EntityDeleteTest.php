<?php

namespace Tests\Cases;

use MoySklad\Components\Http\RequestLog;
use MoySklad\Entities\Products\Product;
use MoySklad\Exceptions\ApiResponseException;
use MoySklad\Exceptions\RequestFailedException;

require_once "TestCase.php";

class EntityDeleteTest extends TestCase{

    public function setUp()
    {
        parent::setUp();
    }

    public function testProductDeletion(){
        $this->methodStart();
        $product = (new Product($this->sklad, [
            "name" => "TestProduct"
        ]))->runCreate();
        $product = Product::byId($this->sklad, $product->id);
        $this->assertTrue(!empty($product->id));
        $this->say("Created product with id: " . $product->id);
        $this->assertTrue($product->delete() === true);
        $this->say("Deleted");
        $this->expectException(ApiResponseException::class);
        $product = Product::byId($this->sklad, $product->id);
        $this->methodEnd();
    }
}