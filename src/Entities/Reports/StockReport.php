<?php

namespace MoySklad\Entities\Reports;

use MoySklad\Components\Specs\QuerySpecs\Reports\StockReportQuerySpecs;
use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\MoySklad;
use MoySklad\Repositories\RequestUrlRepository;

class StockReport extends AbstractReport  {
    public static $reportName = 'stock';

    public static function all(MoySklad $sklad, StockReportQuerySpecs $specs = null){
        return static::queryWithParam($sklad, 'all', $specs);
    }

    public static function byStore(MoySklad $sklad, StockReportQuerySpecs $specs = null){
        return static::queryWithParam($sklad, 'bystore', $specs);
    }

    /**
     * @param MoySklad $sklad
     * @param AbstractDocument $operation
     * @return \stdClass
     */
    public static function byOperation(MoySklad $sklad, AbstractDocument $operation){
        return static::queryWithParam($sklad, 'byoperation', StockReportQuerySpecs::create([
            'operation.id' => $operation->getMeta()->getId()
        ]));
    }
}