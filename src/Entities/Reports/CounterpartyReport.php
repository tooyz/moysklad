<?php

namespace MoySklad\Entities\Reports;

use MoySklad\Components\Specs\QuerySpecs\Reports\CounterpartyReportQuerySpecs;
use MoySklad\Components\Specs\QuerySpecs\Reports\StockReportQuerySpecs;
use MoySklad\Entities\Counterparty;
use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\MoySklad;
use MoySklad\Repositories\ApiUrlRepository;

class CounterpartyReport extends AbstractReport  {
    public static $reportName = 'counterparty';

    public static function get(MoySklad $sklad, CounterpartyReportQuerySpecs $specs = null){
        return static::queryWithParam($sklad, null, $specs);
    }

    public static function byCounterparty(MoySklad $sklad, Counterparty $counterparty){
        return static::queryWithParam($sklad, $counterparty->getMeta()->getId());
    }
}
