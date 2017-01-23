<?php

namespace MoySklad\Components\ListQuery;

use MoySklad\Components\Expand;
use MoySklad\Components\Fields\MetaField;
use MoySklad\Components\FilterQuery;
use MoySklad\Components\Specs\QuerySpecs;
use MoySklad\Lists\EntityList;
use MoySklad\MoySklad;
use MoySklad\Providers\RequestUrlProvider;

class ListQuery{

    protected
        $sklad,
        $entityClass,
        $entityName;
    /**
     * @var Expand $expand
     */
    private $expand;
    private $customQueryUrl = null;

    public function __construct(MoySklad &$skladInstance, $entityClass)
    {
        $this->sklad = $skladInstance;
        $this->entityClass = $entityClass;
        $this->entityName = $entityClass::$entityName;
    }

    /**
     * @param Expand $expand
     * @return $this
     */
    public function withExpand(Expand $expand){
        $this->expand = $expand;
        return $this;
    }

    public function setCustomQueryUrl($customQueryUrl){
        $this->customQueryUrl = $customQueryUrl;
    }

    protected function attachExpand(QuerySpecs &$querySpecs){
        $querySpecs->expand = $this->expand;
        return $querySpecs;
    }

    /**
     * @param array $queryParams
     * @return array|EntityList
     */
    public function get(QuerySpecs $querySpecs = null){
        return $this->filter(null, $querySpecs);
    }

    /**
     * @param array $queryParams
     * @return array|EntityList
     */
    public function search($searchString = '', QuerySpecs $querySpecs = null){
        if ( !$querySpecs ) $querySpecs = QuerySpecs::create([]);
        $this->attachExpand($querySpecs);
        return static::recursiveRequest(function(QuerySpecs $querySpecs, $searchString){
            $query = array_merge($querySpecs->toArray(), [
                "search" => $searchString
            ]);
            return $this->sklad->getClient()->get($this->getQueryUrl(), $query);
        }, $querySpecs, [
            $searchString
        ]);
    }

    /**
     * @param FilterQuery $filterQuery
     * @param QuerySpecs|null $querySpecs
     * @return EntityList
     */
    public function filter( FilterQuery $filterQuery = null, QuerySpecs $querySpecs = null ){
        if ( !$querySpecs ) $querySpecs = QuerySpecs::create([]);
        $this->attachExpand($querySpecs);
        return static::recursiveRequest(function(QuerySpecs $querySpecs, FilterQuery $filterQuery = null){
            if ( $filterQuery ){
                $query = array_merge($querySpecs->toArray(), [
                    "filter" => $filterQuery->getRaw()
                ]);
            } else {
                $query = $querySpecs->toArray();
            }
            return $this->sklad->getClient()->get($this->getQueryUrl(), $query);
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
        $resultingMeta = new MetaField($res->meta);
        $resultingObjects = (new EntityList($this->sklad, $res->rows, $resultingMeta))
            ->map(function($e) {
                return new $this->entityClass($this->sklad, $e);
            });
        if ( $resultingMeta->size > $queryParams->limit + $queryParams->offset ){
            $newQueryParams = $this->recreateQuerySpecs($queryParams);
            if ( $queryParams->maxResults === 0 || $queryParams->maxResults > $requestCounter * $queryParams->limit ){
                $resultingObjects = $resultingObjects->merge(
                    static::recursiveRequest($method, $newQueryParams, $methodArgs, ++$requestCounter)
                );
            }
        }
        return $resultingObjects;
    }

    protected function recreateQuerySpecs(QuerySpecs &$queryParams){
          return QuerySpecs::create([
              "offset" => $queryParams->offset + QuerySpecs::MAX_LIST_LIMIT,
              "limit" => $queryParams->limit,
              "maxResults" => $queryParams->maxResults,
              "expand" => $this->expand
          ]);
    }

    protected function getQueryUrl(){
        return (!empty($this->customQueryUrl)?$this->customQueryUrl: RequestUrlProvider::instance()->getListUrl($this->entityName));
    }
}