<?php

namespace MoySklad\Entities\Pos;

use MoySklad\Components\Http\RequestConfig;
use MoySklad\Interfaces\DoesNotSupportMutation;
use MoySklad\Repositories\ApiUrlRepository;

class RetailStore extends PosEntity implements DoesNotSupportMutation{
    public static $entityName = 'retailstore';
    protected static $customQueryUrl = "admin/retailstore";

    /**
     * @return \stdClass
     */
    public function getAuthToken(){
        return $this->getSkladInstance()->getClient()->post(
            ApiUrlRepository::instance()->getPosAttachTokenUrl($this->id),
            null,
            new RequestConfig([
                "usePosApi" => true
            ])
        )->token;
    }
}