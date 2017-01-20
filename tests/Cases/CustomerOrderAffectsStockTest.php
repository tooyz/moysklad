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
use MoySklad\Entities\Store;
use MoySklad\MoySklad;
use Tests\Config;

require_once "TestCase.php";

class CustomerOrderAffectsStockTest extends TestCase{
    public function setUp()
    {
        parent::setUp();
    }

    public function testCustomerOrderAffectsStock(){
        $this->methodStart();

        $testProductName = $this->makeName("TestProduct");
        $testCounterpartyName = $this->makeName('TestCounterparty');
        $testEnterName = $this->makeName("TestEnter");
        $testCustomerOrder = $this->makeName("TestCustomerOrder");

        $org = Organization::listQuery($this->sklad)->get()->get(0);
        $store = Store::listQuery($this->sklad)->get()->get(0);

        $cp = (new Counterparty($this->sklad, [
            "name" => $testCounterpartyName
        ]))->runCreate();
        $this->say("Cp id:" . $cp->id);
        $product = (new Product($this->sklad, [
            "name" => $testProductName,
            "quantity" => 25
        ]))->runCreate();
        $this->say("Product id:" . $product->id);
        $enter = (new Enter($this->sklad, [
           "name" => $testEnterName
        ]))->setupCreate($org, $store, $product)->runCreate();
        $this->say("Enter id:" . $enter->id );

        $filteredProduct = Assortment::listQuery($this->sklad)->filter(
            (new FilterQuery())->eq("name", $testProductName),
            QuerySpecs::create([
                "maxResults" => 1
            ])
        )->transformItemsToMetaClass()[0];
        $this->assertTrue($filteredProduct->id === $product->id);

        $co = (new CustomerOrder($this->sklad))
            ->setupCreate($cp, $org, $product)->runCreate();

        $this->say("Order id:" . $co->id );

        $enter->delete();
        $co->delete();
        $cp->delete();
        $product->delete();

        $this->methodEnd();
    }
}