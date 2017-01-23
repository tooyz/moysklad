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
            "maxResults" => 0,
            "expand" => null
        ];
    }

    public static function create($specs = [])
    {
        if ( $specs['limit'] > self::MAX_LIST_LIMIT ) $specs['limit'] = self::MAX_LIST_LIMIT;
        $res = parent::create($specs);
        if ( $res->maxResults !== 0 && $res->maxResults < $res->limit ) $res->limit = $res->maxResults;
        return $res;
    }

    public function toArray()
    {
        $res = parent::toArray();
        if ( !empty($this->expand) ){
            $res['expand'] = $this->expand->flatten();
        }
        return $res;
    }
}