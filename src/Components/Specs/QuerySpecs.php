<?php

namespace MoySklad\Components\Specs;


class QuerySpecs extends AbstractSpecs {
    protected static $cachedDefaultSpecs = null;
    const MAX_LIST_LIMIT = 100;

    public function getDefaults()
    {
        return [
            "limit" => self::MAX_LIST_LIMIT,
            "offset" => 0
        ];
    }
}