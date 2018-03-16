<?php

namespace MoySklad\Entities\Audit;

use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Employee;
use MoySklad\MoySklad;
use MoySklad\Registers\ApiUrlRegistry;

class AbstractAudit extends AbstractEntity {
    public static $entityName = "a_audit";
    /**
     * @param \stdClass $attributes
     * @param $skladInstance
     * @return \stdClass
     */
    public static function listQueryResponseAttributeMapper($attributes, $skladInstance){
        if ( isset($attributes->context->employee) ){
            $attributes->context->employee = new Employee($skladInstance, $attributes->context->employee);
        }
        return $attributes;
    }
}
