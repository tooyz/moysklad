<?php

namespace MoySklad\Components\Specs;


class CreationSpecs extends AbstractSpecs {
    protected static $cachedDefaultSpecs = null;
    /**
     * Get possible variables for spec
     *  batch: if true creation request wont be send. Intended to be used with EntityList::massCreate()
     * @return array
     */
    public function getDefaults()
    {
        return [
          "batch" => false
        ];
    }
}