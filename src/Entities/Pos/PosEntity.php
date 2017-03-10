<?php

namespace MoySklad\Entities\Pos;

use MoySklad\Components\Http\RequestConfig;
use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\MoySklad;

abstract class PosEntity extends AbstractEntity{
    public static $entityName = "_a_pos";
    protected static $usePosTokenAuth = false;

    public static function query(MoySklad &$skladInstance, QuerySpecs $querySpecs = null)
    {
        $entityQuery = parent::query($skladInstance, $querySpecs);
        $entityQuery->setRequestOptions(new RequestConfig([
            "usePosApi" => true,
            "usePosToken" => static::$usePosTokenAuth,
            "ignoreRequestBody" => true
        ]));
        return $entityQuery;
    }
}