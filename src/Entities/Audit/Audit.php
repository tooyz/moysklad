<?php

namespace MoySklad\Entities\Audit;

use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use MoySklad\MoySklad;
use MoySklad\Registers\ApiUrlRegistry;

class Audit extends AbstractAudit {
    public static $entityName = "audit";
    /**
     * @var string $customQueryUrl
     */
    protected static $customQueryUrl;

    /**
     * @param MoySklad $skladInstance
     * @return string
     * @throws \Throwable
     */
    public static function getFilters(MoySklad &$skladInstance){
        return (object)$skladInstance->getClient()->get(
            ApiUrlRegistry::instance()->getAuditFiltersUrl()
        );
    }

    /**
     * @param MoySklad $skladInstance
     * @param QuerySpecs|null $querySpecs
     * @return \MoySklad\Components\Query\EntityQuery
     */
    public static function query(MoySklad &$skladInstance, QuerySpecs $querySpecs = null)
    {
        $query = parent::query($skladInstance, $querySpecs)
            ->setCustomQueryUrl(ApiUrlRegistry::instance()->getAuditUrl());
        return $query;
    }
}
