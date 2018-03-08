<?php

namespace MoySklad\Entities\Pos;

use MoySklad\Components\Http\RequestConfig;
use MoySklad\Interfaces\DoesNotSupportMutationInterface;
use MoySklad\Registers\ApiUrlRegistry;

class RetailStore extends PosEntity implements DoesNotSupportMutationInterface{
    public static $entityName = 'retailstore';
    protected static $customQueryUrl = "admin/retailstore";

    /**
     * @return \stdClass
     * @throws \Exception
     * @throws \MoySklad\Exceptions\ApiResponseException
     * @throws \MoySklad\Exceptions\PosTokenException
     * @throws \MoySklad\Exceptions\RequestFailedException
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
