<?php

namespace MoySklad\Entities\Reports;

use MoySklad\Components\Specs\QuerySpecs\Reports\ProfitReportQuerySpecs;
use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\MoySklad;

class ProfitReport extends AbstractReport  {
    public static $reportName = 'profit';

    public static function byProduct(MoySklad $sklad, ProfitReportQuerySpecs $specs = null){
        return static::queryWithParam($sklad, 'byproduct', $specs);
    }

    public static function byVariant(MoySklad $sklad, ProfitReportQuerySpecs $specs = null){
        return static::queryWithParam($sklad, 'byvariant', $specs);
    }
}

