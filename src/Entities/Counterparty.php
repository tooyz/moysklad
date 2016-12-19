<?php

namespace MoySklad\Entities;

use MoySklad\Components\MassRequest;
use MoySklad\Interfaces\ICreatable;

class Counterparty extends AbstractEntity implements ICreatable {
    public static
        $entityName = 'counterparty';

    public function setCreate()
    {

    }

    public function doCreate()
    {
        $mr = new MassRequest($this->getSkladInstance(), $this);
        return $mr->create()[0];
    }
}