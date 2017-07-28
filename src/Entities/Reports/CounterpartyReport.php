<?php

namespace MoySklad\Entities\Reports;

use MoySklad\Components\Specs\QuerySpecs\Reports\CounterpartyReportQuerySpecs;
use MoySklad\Entities\Counterparty;
use MoySklad\MoySklad;

class StockReport extends AbstractReport  {
    public static $reportName = 'counterparty';

    public static function get(MoySklad $sklad, CounterpartyReportQuerySpecs $specs = null){
        return static::queryWithParam($sklad, null, $specs);
    }

    public static function byCounterparty(MoySklad $sklad, Counterparty $counterparty){
        return static::queryWithParam($sklad, $counterparty->getMeta()->getId());
    }
}