<?php

namespace Tests\Cases;

use MoySklad\Components\FilterQuery;
use MoySklad\Components\Http\RequestLog;
use MoySklad\Components\Specs\QuerySpecs;
use MoySklad\Entities\Products\Product;
use MoySklad\Entities\Products\Variant;
use MoySklad\MoySklad;
use Tests\Config;

require_once "TestCase.php";

class FilterTest extends TestCase{
    public function setUp()
    {
        parent::setUp();
    }

    public function testProductFiltration(){
        $this->methodStart();
        $prodArticle = '88005553535' . time();

        $product = (new Product($this->sklad, [
            'article' => $prodArticle,
            'name' => $prodArticle.'_name'
        ]))->buildCreation()->execute();

        $pl = Product::query($this->sklad)->getList();
        $manFpl = $pl->filter(function(Product $e) use($prodArticle){
               return $e->article == $prodArticle;
            });
        echo "Initial product list length: ".$pl->count().",Manually filtered " . $manFpl->count() . " products with article $prodArticle";

        $autoFpl = Product::query($this->sklad)->filter(
            (new FilterQuery())
            ->eq("article", $prodArticle)
        );
        $this->assertEquals(
            $manFpl->count(),
            $autoFpl->count()
        );
        $product->delete();
        $this->methodEnd();
    }
}