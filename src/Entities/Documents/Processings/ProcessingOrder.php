<?php

namespace MoySklad\Entities\Documents\Processings;

use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\Entities\Organization;

class ProcessingOrder extends AbstractDocument {
    public static $entityName = 'processingorder';
    public static function getFieldsRequiredForCreation()
    {
        return [Organization::$entityName,  'processingPlan', 'positions'];
    }
}