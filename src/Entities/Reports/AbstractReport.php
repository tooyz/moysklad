<?php

namespace MoySklad\Entities\Reports;

use MoySklad\Components\Specs\EmptySpecs;
use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\MoySklad;
use MoySklad\Repositories\RequestUrlRepository;

abstract class AbstractReport extends AbstractEntity {
    public static $entityName = 'report';
    public static $reportName = 'a_report';

    protected static function queryWithParam(MoySklad $sklad, $param = null, QuerySpecs $specs = null){
        if ( !$specs ) $specs = EmptySpecs::create();
        if ( $param === null ){
            $url = RequestUrlRepository::instance()->getReportUrl(static::$reportName);
        } else {
            $url = RequestUrlRepository::instance()->getReportWithParamUrl(static::$reportName, $param);
        }
        return $sklad->getClient()->get($url, $specs->toArray());
    }
}