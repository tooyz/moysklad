<?php

namespace MoySklad\Entities\Misc;

use MoySklad\Entities\AbstractEntity;
use MoySklad\MoySklad;
use MoySklad\Repositories\RequestUrlRepository;

abstract class Report extends AbstractEntity  {
    public static $entityName = 'report';

    public static function day(MoySklad $sklad){
        return $sklad->getClient()->get(
            RequestUrlRepository::instance()->getReportUrl('day')
        );
    }

    public static function week(MoySklad $sklad){
        return $sklad->getClient()->get(
            RequestUrlRepository::instance()->getReportUrl('week')
        );
    }

    public static function month(MoySklad $sklad){
        return $sklad->getClient()->get(
            RequestUrlRepository::instance()->getReportUrl('month')
        );
    }
}