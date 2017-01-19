<?php

namespace Tests\Cases;

use MoySklad\Components\FilterQuery;
use MoySklad\Components\Specs\QuerySpecs;
use MoySklad\Entities\Assortment;
use MoySklad\Entities\Counterparty;
use MoySklad\Entities\Movements\Enter;
use MoySklad\Entities\Orders\CustomerOrder;
use MoySklad\Entities\Organization;
use MoySklad\Entities\Products\Product;
use MoySklad\MoySklad;
use Tests\Config;

require_once "TestCase.php";

class CustomerOrderAffectsStockTest extends TestCase{
    public function setUp()
    {
        parent::setUp();
    }

    public function testCustomerOrderAffectsStock(){
        $testProductName = "TestProduct";
        $cp = (new Counterparty($this->sklad, [
            "name" => "CounterpartyTest"
        ]))->doCreate();
        $org = Organization::getList($this->sklad)->get(0);
        $product = (new Product($this->sklad, [
            "name" => $testProductName,
            "quantity" => 25
        ]))->doCreate();
        $enter = (new Enter($this->sklad, [
           "name" => "TestEnter"
        ]))->setCreate($product)->doCreate();
        $filteredProduct = Assortment::filter(
            $this->sklad,
            (new FilterQuery())->eq("name", $testProductName),
            QuerySpecs::create([
                "maxResults" => 1
            ])
        )->transformItemsToMetaClass()[0];

        $this->assertTrue($filteredProduct->id === $product->id);
        /*$co = (new CustomerOrder($this->sklad))
            ->setCreate()
        $co->setCreate()*/
    }
}