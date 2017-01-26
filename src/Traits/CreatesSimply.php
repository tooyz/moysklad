<?php

namespace MoySklad\Traits;

use MoySklad\Components\Specs\CreationSpecs;

trait CreatesSimply{
    /**
     * Runs creation with current EntityFields
     * @param CreationSpecs|null $specs
     * @return static
     */
    public function create(CreationSpecs $specs = null){
        if ( !$specs ) $specs = CreationSpecs::create();
        return $this->runCreateIfNotBatch($specs);
    }
}