<?php

namespace MoySklad\Traits;

use MoySklad\Components\MassRequest;

trait DoesCreation{
    public function doCreate()
    {
        $mr = new MassRequest($this->getSkladInstance(), $this);
        return $mr->create()[0];
    }
}