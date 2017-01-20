<?php

namespace MoySklad\Traits;

use MoySklad\Components\MassRequest;
use MoySklad\Exceptions\SetupCreateWasNotCalled;

trait DoesCreation{
    private $setupCreateWasCalled = false;

    /**
     * @return static
     */
    public function runCreate()
    {
        $this->checkSetupCreateWasCalled();
        $mr = new MassRequest($this->getSkladInstance(), $this);
        return $mr->create()[0];
    }

    private function checkSetupCreateWasCalled(){
        $r = new \ReflectionClass(static::class);
        if ( $r->hasMethod('setupCreate') ){
            if ( !$this->setupCreateWasCalled ){
                throw new SetupCreateWasNotCalled($this);
            }
        }
        $this->setupCreateWasCalled = false;
    }

}