<?php

namespace MoySklad\Entities\Pos;

use MoySklad\Components\Http\RequestConfig;
use MoySklad\Interfaces\DoesNotSupportMutationInterface;
use MoySklad\Registers\ApiUrlRegistry;

class RetailStore extends PosEntity implements DoesNotSupportMutationInterface{
    public static $entityName = 'retailstore';

    public static function boot()
    {
        parent::boot();
        static::$customQueryUrl = ApiUrlRegistry::instance()->getPosRetailStoreQueryUrl();
    }

    /**
     * @return \stdClass
     * @throws \Throwable
     */
    public function getAuthToken(){
        return $this->getSkladInstance()->getClient()->post(
            ApiUrlRegistry::instance()->getPosAttachTokenUrl($this->id),
            null,
            new RequestConfig([
                "usePosApi" => true
            ])
        )->token;
    }
}
