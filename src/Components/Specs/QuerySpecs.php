<?php

namespace MoySklad\Components\Specs;


class QuerySpecs extends AbstractSpecs {
    protected static $cachedDefaultSpecs = null;
    const MAX_LIST_LIMIT = 100;

    public function getDefaults()
    {
        return [
            "limit" => self::MAX_LIST_LIMIT,
            "offset" => 0,
            "maxResults" => 0
        ];
    }

    public static function create($specs = [])
    {
        if ( $specs['limit'] > self::MAX_LIST_LIMIT ) $specs['limit'] = self::MAX_LIST_LIMIT;
        return parent::create($specs);
    }
}