<?php

namespace Tests\Cases;

use MoySklad\Components\Fields\ImageField;
use MoySklad\Entities\Products\Product;

require_once "TestCase.php";

class TestImageAttachment extends TestCase{
    /**
     * @throws \MoySklad\Exceptions\EntityCantBeMutatedException
     * @throws \MoySklad\Exceptions\EntityHasNoIdException
     * @throws \MoySklad\Exceptions\IncompleteCreationFieldsException
     * @throws \MoySklad\Exceptions\InvalidUrlException
     */
    public function testUrlImage(){
        $this->methodStart();
        $product = new Product($this->sklad, [
            "name" => "test_iu" . time(),
        ]);
        $product->attachImage(ImageField::createFromUrl(
            "http://cs6.pikabu.ru/images/previews_comm/2015-06_1/14333110513762.jpg",
            "do_you_think_it's_a_mf_game.jpg"
        ));
        $product = $product->create();
        $this->assertTrue(!empty($product->image));
        $product->delete();
        $this->methodEnd();
    }

    public function testLocalImage(){
        $this->methodStart();
        $product = new Product($this->sklad, [
            "name" => "test_il" . time(),
        ]);
        $product->attachImage(ImageField::createFromPath(
            dirname(__FILE__) . "/../Assets/kitty.jpg"
        ));
        $product = $product->create();
        $this->assertTrue(!empty($product->image));
        $product->delete();
        $this->methodEnd();
    }
}
