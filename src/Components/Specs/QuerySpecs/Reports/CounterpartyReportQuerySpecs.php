<?php

namespace MoySklad\Components\Specs\QuerySpecs\Reports;


use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;

class CounterpartyReportQuerySpecs extends QuerySpecs {
    protected static $cachedDefaultSpecs = null;

    public function getDefaults()
    {
        $res = parent::getDefaults();
        $res->id = null;
        return $res;
    }


}
