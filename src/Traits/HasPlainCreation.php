<?php

namespace MoySklad\Traits;

use MoySklad\Components\Specs\CreationSpecs;

trait HasPlainCreation{
    /**
     * @param CreationSpecs|null $specs
     * @return static
     */
    public function create(CreationSpecs $specs = null){
        if ( !$specs ) $specs = CreationSpecs::create();
        return $this->runCreateIfNotBatch($specs);
    }
}