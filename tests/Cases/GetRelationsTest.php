<?php

namespace Tests\Cases;

use MoySklad\Components\Http\RequestLog;
use MoySklad\Entities\Counterparty;
use MoySklad\Exceptions\Relations\RelationDoesNotExistException;
use MoySklad\Lists\RelationEntityList;

require_once "TestCase.php";

class GetRelationsTest extends TestCase{

    public function setUp()
    {
        parent::setUp();
    }

    public function testGetSingleRelation(){
        $cp = Counterparty::listQuery($this->sklad)->get()->get(0);
        $accounts = $cp->relations->getListQuery('accounts')->get();
        $this->assertInstanceOf(RelationEntityList::class, $accounts);
        $owner = $cp->relations->loadSingleRelation('owner');
        $this->assertTrue(!empty($owner->id));
        $this->expectException(RelationDoesNotExistException::class);
        $cp->relations->loadSingleRelation('undefined');
    }
}