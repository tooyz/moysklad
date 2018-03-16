<?php

namespace Tests\Cases;

use MoySklad\Entities\Documents\Inventory;
use MoySklad\Entities\Organization;
use MoySklad\Entities\Store;

require_once "TestCase.php";

class ExportTest extends TestCase{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @throws \Exception
     * @throws \MoySklad\Exceptions\EntityCantBeMutatedException
     * @throws \MoySklad\Exceptions\EntityHasNoIdException
     * @throws \Throwable
     */
    public function testGetEmbeddedTemplates(){
        $this->methodStart();
        $organization = Organization::query($this->sklad)->getList()->get(0);
        $store = Store::query($this->sklad)->getList()->get(0);
        /**
         * @var Inventory $inv
         */
        $inv = (new Inventory($this->sklad))->buildCreation()->addOrganization($organization)->addStore($store)->execute();
        $templates = $inv->getExportEmbeddedTemplates();
        $export = $inv->createExport(
            $templates->map(function($e){
                $e->count = 3;
                return $e;
            })
        );
        $inv->delete(true);;
        $this->assertNotNull($export->getFileLink());
        $this->methodEnd();
    }
}
