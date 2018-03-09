<?php

namespace Tests\Cases;

use MoySklad\Components\Expand;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use MoySklad\Entities\Employee;
use MoySklad\Utils\CommonDate;

require_once "TestCase.php";

class SpecsTest extends TestCase{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @throws \Exception
     */
    public function testSpecsMerge(){
        $this->methodStart();

        $specs = LinkingSpecs::create([
            'name' => "name",
            'fields' => ['asd'],
            'multiple' => true
        ]);
        $otherSpecs = LinkingSpecs::create([
            'excludedFields' => ['qwe'],
            'name' => 'name2'
        ]);
        $mergedSpecs = $specs->mergeWith($otherSpecs);
        $this->assertTrue($mergedSpecs->name === 'name2');
        $this->assertTrue($mergedSpecs->fields === ['asd']);
        $this->assertTrue($mergedSpecs->excludedFields === ['qwe']);
        $this->assertTrue($mergedSpecs->multiple === true);

        $specs = QuerySpecs::create([
            "offset" => 10,
            "maxResults" => 0,
            "expand" => null,
            "updatedFrom" => new CommonDate('2000-10-10 10:10'),
            "updatedBy" => new Employee($this->sklad, ['name' => 'Vasya']),
        ]);
        $otherSpecs = QuerySpecs::create([
            "offset" => 0,
            "maxResults" => 100,
            "expand" => Expand::create([]),
            "updatedBy" => new Employee($this->sklad, ['name' => 'Petya']),
        ]);
        $mergedSpecs = $specs->mergeWith($otherSpecs);
        $this->assertTrue($mergedSpecs->offset === 10);
        $this->assertTrue($mergedSpecs->maxResults === 100);
        $this->assertTrue($mergedSpecs->expand instanceof Expand);
        $this->assertTrue($mergedSpecs->updatedBy->name === 'Petya');
        $this->assertTrue($mergedSpecs->updatedFrom === '2000-10-10 10:10:00');

        $this->methodEnd();
    }
}
