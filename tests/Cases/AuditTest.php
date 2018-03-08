<?php

namespace Tests\Cases;

use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use MoySklad\Entities\Audit\Audit;
use MoySklad\Entities\Audit\AuditEvent;
use MoySklad\Entities\Documents\Orders\CustomerOrder;
use MoySklad\Lists\RelationEntityList;

require_once "TestCase.php";

class AuditTest extends TestCase{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testAuditsList(){
        $this->methodStart();
        $audits = Audit::query($this->sklad, QuerySpecs::create([
            'maxResults' => 3
        ]))->getList();
        /**
         * @var RelationEntityList $events
         */
        $audit = $audits->get(0);
        $this->assertInstanceOf(Audit::class, $audit);
        $events = $audit->getAuditEvents();
        $this->assertInstanceOf(AuditEvent::class, $events->get(0));
        Audit::getFilters($this->sklad);
        $this->methodEnd();
    }
}
