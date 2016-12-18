<?php

namespace Tests\Cases;

use MoySklad\Components\FilterQuery;
use MoySklad\Components\Http\RequestLog;
use MoySklad\Entities\Products\Product;
use MoySklad\MoySklad;
use Tests\Config;

require_once "TestCase.php";

class FilterTest extends TestCase{
    public function setUp()
    {
        parent::setUp();
    }

    public function testProductFiltration(){
        $article = '8800555';
        $pl = Product::getList($this->sklad);
        $manFpl = $pl->filter(function(Product $e) use($article){
               return $e->article == $article;
            });
        echo "Initial product list length: ".$pl->count().",Manually filtered " . $manFpl->count() . " products with article $article";

        $autoFpl = Product::filter(
            $this->sklad,
            (new FilterQuery())
            ->eq("article", $article)
        );
        $this->assertEquals(
            $manFpl->count(),
            $autoFpl->count()
        );
    }
}