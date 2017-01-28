<?php

namespace MoySklad\Entities\Reports;

use MoySklad\MoySklad;
use MoySklad\Repositories\RequestUrlRepository;

class DashboardReport extends AbstractReport  {
    public static $reportName = 'dashboard';

    public static function day(MoySklad $sklad){
        return static::queryWithParam($sklad, 'day');
    }

    public static function week(MoySklad $sklad){
        return static::queryWithParam($sklad, 'week');
    }

    public static function month(MoySklad $sklad){
        return static::queryWithParam($sklad, 'month');
    }
}