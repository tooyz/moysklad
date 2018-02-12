<?php

namespace Tests\Cases;

use MoySklad\Entities\Reports\DashboardReport;

require_once "TestCase.php";

class ReportTest extends TestCase{
    public function testDashboardReport(){
        $report = DashboardReport::day($this->sklad);
        $this->assertTrue(isset($report->sales));
    }
}
