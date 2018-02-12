<?php

namespace MoySklad\Entities\Documents\CommissionReports;

use MoySklad\Entities\Contract;
use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\Entities\Organization;

class AbstractCommissionReport extends AbstractDocument{
    public static $entityName = 'a_commissionreport';
    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName,  'agent', Contract::$entityName, 'commissionPeriodStart', 'commissionPeriodEnd'];
    }
}
