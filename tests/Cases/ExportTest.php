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
     */
    public function testGetEmbeddedTemplates(){
        $this->methodStart();
        $organization = Organization::query($this->sklad)->getList()->get(0);
        $store = Store::query($this->sklad)->getList()->get(0);
        /**
         * @var Inventory $inv
         */
        $inv = (new Inventory($this->sklad))->buildCreation()->addOrganization($organization)->addStore($store)->execute();
        $export = $inv->getExportEmbeddedTemplates();
        $inv->delete(true);
        $this->assertNotNull($export->get(0)->getContent());
        $this->methodEnd();
    }
}
