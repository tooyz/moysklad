<?php

namespace MoySklad\Components\Query;

use MoySklad\Components\Expand;
use MoySklad\Components\Fields\MetaField;
use MoySklad\Components\FilterQuery;
use MoySklad\Components\Http\RequestConfig;
use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use MoySklad\Lists\EntityList;
use MoySklad\MoySklad;
use MoySklad\Registers\ApiUrlRegistry;
use MoySklad\Traits\AccessesSkladInstance;

abstract class AbstractQuery{
    use AccessesSkladInstance;
    
    protected
        $entityClass,
        $entityName,
        $querySpecs,
        $requestOptions,
        $responseAttributes,
        $responseAttributesMapper;
    /**
     * @var Expand $expand
     */
    protected $expand = null;
    private $customQueryUrl = null;
    protected static $entityListClass;

    /**
     * AbstractQuery constructor.
     * @param MoySklad $skladInstance
     * @param $entityClass
     * @param QuerySpecs|null $querySpecs
     * @throws \Exception
     */
    public function __construct(MoySklad &$skladInstance, $entityClass, QuerySpecs $querySpecs = null)
    {
        $this->skladHashCode = $skladInstance->hashCode();
        $this->entityClass = $entityClass;
        $this->entityName = $entityClass::$entityName;
        if ( !$querySpecs ) $querySpecs = QuerySpecs::create([]);
        $this->querySpecs = $querySpecs;
        $this->responseAttributes = ['meta' => null];
    }

    /**
     * Add expand to query
     * @param Expand $expand
     * @return $this
     */
    public function withExpand(Expand $expand){
        $this->expand = $expand;
        return $this;
    }

    /**
     * Url that will be used instead of default list url
     * @param $customQueryUrl
     * @return $this
     */
    public function setCustomQueryUrl($customQueryUrl){
        $this->customQueryUrl = $customQueryUrl;
        return $this;
    }

    /**
     * @param RequestConfig $options
     * @return $this
     */
    public function setRequestOptions(RequestConfig $options){
        $this->requestOptions = $options;
        return $this;
    }

    /**
     * @param string|callable $fnOrClass
     * @param $method
     * @return $this
     */
    public function setResponseAttributesMapper($fnOrClass, $method = null){
        if ( is_string($fnOrClass) ){
            $fn = "$fnOrClass::$method";
        } else {
            $fn = $fnOrClass;
        }
        $this->responseAttributesMapper = $fn;
        return $this;
    }

    /**
     * Get list of entities
     * @return EntityList
     */
    public function getList(){
        return $this->filter(null);
    }

    /**
     * Search within list of entities
     * @param string $searchString
     * @return EntityList
     */
    public function search($searchString = ''){
        $this->attachExpand($this->querySpecs);
        $queryResult = static::recursiveRequest(function(QuerySpecs $querySpecs, $searchString){
            $query = array_merge($querySpecs->toArray(), [
                "search" => $searchString
            ]);
            return $this->getSkladInstance()->getClient()->get($this->getQueryUrl(), $query, $this->requestOptions);
        }, $this->querySpecs, [
            $searchString
        ]);
        $queryResult->replaceAttributes($this->mapResponseAttributes());
        return $queryResult;
    }

    /**
     * Filter within list of entities
     * @param FilterQuery|null $filterQuery
     * @return EntityList
     */
    public function filter( FilterQuery $filterQuery = null ){
        $this->attachExpand($this->querySpecs);
        $queryResult = static::recursiveRequest(function(QuerySpecs $querySpecs, FilterQuery $filterQuery = null){
            if ( $filterQuery ){
                $query = array_merge($querySpecs->toArray(), [
                    "filter" => $filterQuery->getRaw()
                ]);
            } else {
                $query = $querySpecs->toArray();
            }
            return $this->getSkladInstance()->getClient()->get($this->getQueryUrl(), $query, $this->requestOptions);
        }, $this->querySpecs, [
            $filterQuery
        ]);
        $queryResult->replaceAttributes($this->mapResponseAttributes());
        return $queryResult;
    }

    /**
     * Used for sending multiple list requests
     * @param callable $method
     * @param QuerySpecs $queryParams
     * @param array $methodArgs
     * @param int $requestCounter
     * @return EntityList
     */
    protected function recursiveRequest(
        callable $method, QuerySpecs $queryParams, $methodArgs = [], $requestCounter = 1
    ){
        $res = call_user_func_array($method, array_merge([$queryParams], $methodArgs));
        $resultingMeta = $this->mapIntermediateResponseAttributes($res);
        /**
         * @var EntityList $resultingObjects
         */
        $resultingObjects = (new static::$entityListClass($this->getSkladInstance(), $res->rows, $resultingMeta))
            ->map(function($e) {
                return new $this->entityClass($this->getSkladInstance(), $e);
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

    /**
     * Get previous QuerySpecs and increase offset
     * @param QuerySpecs $queryParams
     * @return QuerySpecs
     */
    protected function recreateQuerySpecs(QuerySpecs &$queryParams){
          return QuerySpecs::create([
              "offset" => $queryParams->offset + QuerySpecs::MAX_LIST_LIMIT,
              "limit" => $queryParams->limit,
              "maxResults" => $queryParams->maxResults,
              "expand" => $this->expand
          ]);
    }

    /**
     * Get default list query url, or use custom one
     * @return null|string
     */
    protected function getQueryUrl(){
        return (!empty($this->customQueryUrl)?
            $this->customQueryUrl:
            ApiUrlRegistry::instance()->getListUrl($this->entityName));
    }

    /**
     * Attach added expand to specs
     * @param QuerySpecs $querySpecs
     * @return QuerySpecs
     */
    protected function attachExpand(QuerySpecs &$querySpecs){
        if ( $this->expand !== null ){
            $querySpecs->expand = $this->expand;
        }
        return $querySpecs;
    }

    /**
     * @param $response
     * @return MetaField
     */
    protected function mapIntermediateResponseAttributes(&$response){
        foreach ( $response as $key => $responseAttribute ){
            if ( $key === 'meta' ) {
                $this->responseAttributes['meta'] = new MetaField($responseAttribute);
            } else if ( $key !== 'rows' ) {
                $this->responseAttributes[$key] = $responseAttribute;
            }
        }
        return $this->responseAttributes['meta'];
    }

    public function mapResponseAttributes(){
        $result = (object)$this->responseAttributes;
        if ( $this->responseAttributesMapper ){
            $fn = $this->responseAttributesMapper;
            return $fn($result, $this->getSkladInstance());
        }
        return $result;
    }
}
