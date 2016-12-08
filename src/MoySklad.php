<?php

namespace MoySklad;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Product;
use MoySklad\Exceptions\UnknownEntityException;
use MoySklad\Utils\MoySkladClient;

class MoySklad{

    private
        $client;

    public function __construct(
        $login,
        $password
    )
    {
        $this->client = new MoySkladClient($login, $password);
    }

    /**
     * @return MoySkladClient
     */
    public function getClient(){
        return $this->client;
    }
}