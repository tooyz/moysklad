<?php

namespace Tests\Cases;

use MoySklad\Components\Expand;
use MoySklad\Components\Http\RequestLog;
use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Assortment;
use MoySklad\Entities\Employee;
use MoySklad\Entities\Group;
use MoySklad\Entities\Pos\RetailStore;
use MoySklad\Entities\Products\Product;
use MoySklad\Entities\Products\Service;
use MoySklad\Lists\EntityList;

require_once "TestCase.php";

class PosTest extends TestCase{
    /**
     * @throws \Throwable
     */
    public function testRetailStore(){
//        $retails = RetailStore::query($this->sklad)->getList();
//        if ( $retails->count() ){
//            /**
//             * @var RetailStore $retail
//             */
//            $retail = $retails->get(0);
//            $token = $retail->getAuthToken();
//            $this->assertNotNull($token);
//        }
    }
}
