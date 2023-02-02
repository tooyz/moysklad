<?php

namespace MoySklad\Components\Specs\QuerySpecs\Reports;


use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;

class ProfitReportQuerySpecs extends QuerySpecs {
    protected static $cachedDefaultSpecs = null;

    public function getDefaults()
    {
        $res = parent::getDefaults();
        $res['momentFrom'] = null;
        $res['momentTo'] = null;
        $res['filter'] = null;
        $res['product'] = null;
        $res['counterparty'] = null;
        $res['organization'] = null;
        $res['store'] = null;
        $res['project'] = null;
        $res['retailStore'] = null;
        $res['supplier'] = null;
        $res['salesChannel'] = null;
        return $res;
    }
}
