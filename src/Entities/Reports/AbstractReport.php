<?php

namespace MoySklad\Entities\Reports;

use MoySklad\Components\Specs\EmptySpecs;
use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Interfaces\DoesNotSupportMutationInterface;
use MoySklad\MoySklad;
use MoySklad\Registers\ApiUrlRegistry;

abstract class AbstractReport extends AbstractEntity implements DoesNotSupportMutationInterface {
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
            $url = ApiUrlRegistry::instance()->getReportUrl(static::$reportName);
        } else {
            $url = ApiUrlRegistry::instance()->getReportWithParamUrl(static::$reportName, $param);
        }
        return $sklad->getClient()->get($url, $specs->toArray());
    }
}
