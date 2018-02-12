<?php

namespace MoySklad\Entities\Documents\Invoices;

use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\Entities\Organization;

class AbstractInvoice extends AbstractDocument{
    public static $entityName = 'a_invoice';
    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName,  'agent'];
    }
}
