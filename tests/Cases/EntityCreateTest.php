<?php

namespace Tests\Cases;


use MoySklad\Components\Http\RequestLog;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Entities\Currency;
use MoySklad\Entities\Folders\ProductFolder;
use MoySklad\Entities\Misc\Characteristics;
use MoySklad\Entities\Products\Product;
use MoySklad\Entities\Products\Variant;

require_once "TestCase.php";

class EntityCreateTest extends TestCase{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @throws \Exception
     * @throws \MoySklad\Exceptions\EntityCantBeMutatedException
     * @throws \MoySklad\Exceptions\EntityHasNoIdException
     * @throws \MoySklad\Exceptions\IncompleteCreationFieldsException
     */
    public function testCreation(){
        $this->methodStart();

        $product = new Product($this->sklad, [
            "name" => "random_name_" . time(),
            "salePrices" => [[
                "value" => 99,
                "priceType" => "Цена продажи"
            ]]
        ]);
        $product = $product->create();
        $this->assertTrue(isset($product->id));

        $variant = new Variant($this->sklad, [
            "name" =>  "(оверспелый, жёлтый)"
        ]);
        $characteristics1 = new Characteristics($this->sklad, [
           'name' => "цвет",
           'value' => 'желтый'
        ]);
        $characteristics2 = new Characteristics($this->sklad, [
            'name' => "зрелость",
            'value' => 'оверспелый'
        ]);
        $linkSpecs = LinkingSpecs::create(["multiple" => true]);
        $variant = $variant->buildCreation()
            ->addProduct($product)
            ->addCharacteristics($characteristics1, $linkSpecs)
            ->addCharacteristics($characteristics2, $linkSpecs)
            ->execute();

        $this->assertTrue(isset($variant->id));

        $variant->delete(true);
        $product->delete(true);

        $this->methodEnd();
    }
}
