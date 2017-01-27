<?php

namespace Tests\Cases;

use MoySklad\Components\FilterQuery;
use MoySklad\Components\Specs\QuerySpecs;
use MoySklad\Entities\Assortment;
use MoySklad\Entities\Counterparty;
use MoySklad\Entities\Documents\Movements\Enter;
use MoySklad\Entities\Documents\Orders\CustomerOrder;
use MoySklad\Entities\Organization;
use MoySklad\Entities\Products\Product;
use MoySklad\Entities\Store;
use MoySklad\Lists\EntityList;
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
        ]))->buildCreation()->execute();
        $this->say("Cp id:" . $cp->id);
        $product = (new Product($this->sklad, [
            "name" => $testProductName,
            "quantity" => 25
        ]))->buildCreation()->execute();
        $this->say("Product id:" . $product->id);
        $enter = (new Enter($this->sklad, [
           "name" => $testEnterName
        ]))->buildCreation()->
            addOrganization($org)->
            addStore($store)->
            addPositionList(new EntityList($this->sklad, $product))->
            execute();
        $this->say("Enter id:" . $enter->id );

        $filteredProduct = Assortment::listQuery($this->sklad,QuerySpecs::create([
            "maxResults" => 1
        ]))->filter(
            (new FilterQuery())->eq("name", $testProductName)
        )->transformItemsToMetaClass()[0];
        $this->assertTrue($filteredProduct->id === $product->id);

        $co = (new CustomerOrder($this->sklad, [
            "name" => "TestOrder"
        ]))
            ->buildCreation()
            ->addCounterparty($cp)
            ->addOrganization($org)
            ->addPositionList(new EntityList($this->sklad, $product))
            ->execute();

        $this->say("Order id:" . $co->id );

        $enter->delete();
        $co->delete();
        $cp->delete();
        $product->delete();

        $this->methodEnd();
    }
}