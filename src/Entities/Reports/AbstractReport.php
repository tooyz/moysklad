<?php

namespace MoySklad\Entities\Reports;

use MoySklad\Components\Specs\EmptySpecs;
use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Interfaces\PreventsMutation;
use MoySklad\MoySklad;
use MoySklad\Repositories\ApiUrlRepository;

abstract class AbstractReport extends AbstractEntity implements PreventsMutation {
    public static $entityName = 'report';
    public static $reportName = 'a_report';

    /**
     * @param MoySklad $sklad
     * @param null $param
     * @param QuerySpecs|null $specs
     * @return \stdClass
     */
    protected static function queryWithParam(MoySklad $sklad, $param = null, QuerySpecs $specs = null){
        if ( !$specs ) $specs = EmptySpecs::create();
        if ( $param === null ){
            $url = ApiUrlRepository::instance()->getReportUrl(static::$reportName);
        } else {
            $url = ApiUrlRepository::instance()->getReportWithParamUrl(static::$reportName, $param);
        }
        return $sklad->getClient()->get($url, $specs->toArray());
    }
}