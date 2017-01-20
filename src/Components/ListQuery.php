<?php

namespace MoySklad\Components;

use MoySklad\Components\Specs\QuerySpecs;
use MoySklad\Lists\EntityList;
use MoySklad\MoySklad;
use MoySklad\Providers\RequestUrlProvider;

class ListQuery{

    private
        $sklad,
        $entityClass,
        $entityName;

    public function __construct(MoySklad &$skladInstance, $entityClass)
    {
        $this->sklad = $skladInstance;
        $this->entityClass = $entityClass;
        $this->entityName = $entityClass::$entityName;
    }

    /**
     * @param array $queryParams
     * @return array|EntityList
     */
    public function get(QuerySpecs $querySpecs = null){
        return $this->filter(null, $querySpecs);
    }

    /**
     * @param FilterQuery $filterQuery
     * @param QuerySpecs|null $querySpecs
     * @return EntityList
     */
    public function filter( FilterQuery $filterQuery = null, QuerySpecs $querySpecs = null ){
        if ( !$querySpecs ) $querySpecs = QuerySpecs::create([]);
        return static::recursiveRequest(function(QuerySpecs $querySpecs, FilterQuery $filterQuery = null){
            if ( $filterQuery ){
                $query = array_merge($querySpecs->toArray(), [
                    "filter" => $filterQuery->getRaw()
                ]);
            } else {
                $query = $querySpecs->toArray();
            }
            return $this->sklad->getClient()->get(
                RequestUrlProvider::instance()->getListUrl($this->entityName), $query
            );
        }, $querySpecs, [
            $filterQuery
        ]);
    }

    /**
     * @param callable $method
     * @param QuerySpecs $queryParams
     * @param array $methodArgs
     * @return EntityList
     */
    protected function recursiveRequest(
        callable $method, QuerySpecs $queryParams, $methodArgs = [], $requestCounter = 1
    ){
        $res = call_user_func_array($method, array_merge([$queryParams], $methodArgs));
        $resultingObjects = (new EntityList($this->sklad, $res->rows))
            ->map(function($e) {
                return new $this->entityClass($this->sklad, $e);
            });
        if ( $res->meta->size > $queryParams->limit + $queryParams->offset ){
            $newQueryParams = QuerySpecs::create([
                "offset" => $queryParams->offset + QuerySpecs::MAX_LIST_LIMIT,
                "limit" => $queryParams->limit,
                "maxResults" => $queryParams->maxResults
            ]);
            if ( $queryParams->maxResults === 0 || $queryParams->maxResults > $requestCounter * $queryParams->limit ){
                $resultingObjects = $resultingObjects->merge(
                    static::recursiveRequest($method, $newQueryParams, $methodArgs, ++$requestCounter)
                );
            }
        }
        return $resultingObjects;
    }
}