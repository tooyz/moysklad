<?php

namespace MoySklad;

use MoySklad\Entities\Entity;
use MoySklad\Utils\MoySkladClient;

class MoySklad{

    public function __construct(
        $login,
        $password
    )
    {
        $this->client = new MoySkladClient($login, $password);
        Entity::setClientInstance($this->client);
    }
}