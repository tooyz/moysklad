<?php

namespace MoySklad\Components\Specs\QuerySpecs;

use MoySklad\Components\Specs\AbstractSpecs;
use MoySklad\Utils\CommonDate;

class QuerySpecs extends AbstractSpecs {
    protected static $cachedDefaultSpecs = null;
    const MAX_LIST_LIMIT = 1000;

    /**
     * Get possible variables for spec, will be sent as query string
     *  limit: max results per request
     *  offset: get results with offset
     *  maxResults: get only this amount of results. If 0 - unlimited
     *  expand: use expand parameter to get chosen relations
     *  updatedFrom: entity should be updated from date
     *  updatedTo: entity should be updated up to date
     *  updatedBy: entity should be updated by employee
     * @return array
     */
    public function getDefaults()
    {
        return [
            "limit" => static::MAX_LIST_LIMIT,
            "offset" => 0,
            "maxResults" => 0,
            "expand" => null,
            "updatedFrom" => null,
            "updatedTo" => null,
            "updatedBy" => null,
            "order" => null,
            "search" => null
        ];
    }

    /**
     * Fixes wrong limit spec. Fixes maxLimit lower then limit
     * @param array $specs
     * @return static
     * @throws \Exception
     */
    public static function create($specs = [])
    {
        if ( isset($specs['limit']) && $specs['limit'] > self::MAX_LIST_LIMIT ) $specs['limit'] = self::MAX_LIST_LIMIT;
        $res = parent::create($specs);
        if ( $res->maxResults !== 0 && $res->maxResults < $res->limit ) {
            $res->limit = $res->maxResults;
        }
        try{
            foreach ( ['updatedFrom', 'updatedTo'] as $date ){
                if ( $res->{$date} !== null ) {
                    if ( is_string($res->{$date}) ) $res->{$date} = new CommonDate($res->{$date});
                    $res->{$date} = $res->{$date}->format();
                }
            }
        } catch ( \Throwable $e ){
            throw new \Exception('"updatedFrom" and "updatedTo" specs should be instances of "'.CommonDate::class.'" class');
        }

        return $res;
    }

    /**
     * Converts itself to array, converts expand spec to string
     * @return array
     */
    public function toArray()
    {
        $res = parent::toArray();
        if ( !empty($this->expand) ){
            $res['expand'] = $this->expand->flatten();
        }
        foreach ( $res as $k=>$v ){
            if ( $v === null ) unset($res->{$k});
            if ( $v instanceof CommonDate ){
                $res->{$k} = $v->format();
            }
        }
        return $res;
    }
}
