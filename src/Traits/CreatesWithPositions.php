<?php

namespace MoySklad\Traits;

use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Lists\EntityList;

trait CreatesWithPositions{
    use LinksPositionsForCreation;
    /**
     * Runs creation with current EntityFields
     * @param CreationSpecs|null $specs
     * @return static
     */
    public function create(EntityList $positions = null, CreationSpecs $specs = null){
        if ( !$specs ) $specs = CreationSpecs::create();
        $this->linkPositionsForCreation($positions);
        return $this->runCreateIfNotBatch($specs);
    }
}