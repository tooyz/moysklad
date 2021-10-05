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
        $res['store.id'] = null;
        $res['product.id'] = null;
        $res['counterparty.id'] = null;
        $res['organization.id'] = null;
        $res['store.id'] = null;
        $res['project.id'] = null;
        $res['retailStore.id'] = null;
        return $res;
    }
}
