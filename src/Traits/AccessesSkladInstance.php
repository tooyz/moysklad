<?php

namespace MoySklad\Traits;


use MoySklad\MoySklad;

trait AccessesSkladInstance{
    /**
     * @var string
     */
    protected $skladHashCode;

    /**
     * Get MoySklad instance used for constructing entity
     * @return MoySklad
     */
    public function getSkladInstance(){
        return MoySklad::findInstanceByHash($this->skladHashCode);
    }
}
